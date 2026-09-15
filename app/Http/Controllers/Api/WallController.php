<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WallPost;
use App\Models\WallComment;
use App\Models\WallLike;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class WallController extends Controller
{
    public function feed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $now = now()->toDateString();

        // Get simple list (no pagination) of today's wall posts and valid general posts
        $posts = WallPost::with(['author', 'targetEmployee', 'comments.employee', 'likes'])
            ->where('business_id', $request->business_id)
            ->where(function ($query) use ($now) {
                // Regular posts (birthday, anniversary, joinee) created today
                $query->where(function ($q) use ($now) {
                    $q->where('type', '!=', 'general')
                      ->whereDate('created_at', $now);
                })
                // General announcements that are currently valid today
                ->orWhere(function ($q) use ($now) {
                    $q->where('type', 'general')
                      ->where(function ($q2) use ($now) {
                          $q2->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
                      })
                      ->where(function ($q2) use ($now) {
                          $q2->whereNull('valid_to')->orWhere('valid_to', '>=', $now);
                      });
                });
            })
            ->latest()
            ->get();

        $mappedPosts = $posts->map(function($p) {
            return [
                'post_id' => $p->id,
                'author_name' => $p->author ? ($p->author->first_name . ' ' . $p->author->last_name) : 'System',
                'author_avatar' => $p->author ? ($p->author->profile_picture_url ?? null) : null,
                'content' => $p->content,
                'image_url' => $p->image_url,
                'likes_count' => $p->likes ? $p->likes->count() : 0,
                'comments_count' => $p->comments ? $p->comments->count() : 0,
                'created_at' => $p->created_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Wall feed fetched successfully',
            'data' => $mappedPosts
        ]);
    }

    public function storePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'employee_id' => 'required|exists:employees,id',
            'content' => 'required|string',
            'image_url' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Employee::with('loginAccess')->find($request->employee_id);

        if (!$employee->loginAccess || !$employee->loginAccess->allow_wall_posting) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to post on the wall.'
            ], 403);
        }

        $post = WallPost::create([
            'business_id' => $request->business_id,
            'employee_id' => $employee->id,
            'type' => 'general',
            'content' => $request->content,
            'image_url' => $request->image_url,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'data' => $post->load('author', 'targetEmployee', 'comments', 'likes')
        ]);
    }

    public function storeComment(Request $request, $post_id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'comment' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = WallPost::find($post_id);
        if (!$post) {
            return response()->json(['status' => false, 'message' => 'Post not found'], 404);
        }

        $employee = Employee::with('loginAccess')->find($request->employee_id);

        if (!$employee->loginAccess || !$employee->loginAccess->allow_wall_comments) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to comment.'
            ], 403);
        }

        $comment = WallComment::create([
            'wall_post_id' => $post->id,
            'employee_id' => $employee->id,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment added successfully',
            'data' => $comment->load('employee')
        ]);
    }

    public function toggleLike(Request $request, $post_id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = WallPost::find($post_id);
        if (!$post) {
            return response()->json(['status' => false, 'message' => 'Post not found'], 404);
        }

        $like = WallLike::where('wall_post_id', $post->id)
                        ->where('employee_id', $request->employee_id)
                        ->first();

        if ($like) {
            $like->delete();
            return response()->json([
                'status' => true,
                'message' => 'Post unliked successfully'
            ]);
        } else {
            WallLike::create([
                'wall_post_id' => $post->id,
                'employee_id' => $request->employee_id,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Post liked successfully'
            ]);
        }
    }

    public function destroy(Request $request, $post_id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $post = WallPost::find($post_id);
        if (!$post) {
            return response()->json(['status' => false, 'message' => 'Post not found'], 404);
        }

        $employee = Employee::with('loginAccess')->find($request->employee_id);

        $isAdmin = $employee->loginAccess && $employee->loginAccess->make_wall_admin;
        $isAuthor = $post->employee_id == $employee->id;

        if (!$isAdmin && !$isAuthor) {
            return response()->json(['status' => false, 'message' => 'You do not have permission to delete this post.'], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    public function updatePost(Request $request, $post_id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'content' => 'required|string',
            'image_url' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $post = WallPost::find($post_id);
        if (!$post) {
            return response()->json(['status' => false, 'message' => 'Post not found'], 404);
        }

        if ($post->employee_id != $request->employee_id) {
            return response()->json(['status' => false, 'message' => 'You do not have permission to edit this post.'], 403);
        }

        $post->update([
            'content' => $request->content,
            'image_url' => $request->has('image_url') ? $request->image_url : $post->image_url,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'data' => $post->load('author', 'targetEmployee', 'comments', 'likes')
        ]);
    }

    public function destroyComment(Request $request, $comment_id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $comment = WallComment::find($comment_id);
        if (!$comment) {
            return response()->json(['status' => false, 'message' => 'Comment not found'], 404);
        }

        $employee = Employee::with('loginAccess')->find($request->employee_id);
        $isAdmin = $employee->loginAccess && $employee->loginAccess->make_wall_admin;
        $isAuthor = $comment->employee_id == $employee->id;

        if (!$isAdmin && !$isAuthor) {
            return response()->json(['status' => false, 'message' => 'You do not have permission to delete this comment.'], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }

    public function suggestEmployees(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'search' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $query = Employee::where('business_id', $request->business_id)->where('status', 'Active');
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('employee_code', 'like', '%' . $request->search . '%');
            });
        }

        $employees = $query->select('id', 'first_name', 'last_name', 'employee_code')->take(10)->get();

        return response()->json([
            'status' => true,
            'message' => 'Suggestions fetched successfully',
            'data' => $employees
        ]);
    }
}
