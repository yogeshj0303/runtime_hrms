<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\WallPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WallWebController extends Controller
{
    public function index()
    {
        $businessId = Auth::user()->active_business_id;
        $today = Carbon::today();
        
        // 1. Upcoming Birthdays (Today & Tomorrow)
        $birthdays = Employee::where('business_id', $businessId)
            ->whereHas('profile', function ($query) use ($today) {
                $query->whereRaw('DATE_FORMAT(dob, "%m-%d") IN (?, ?)', [
                    $today->format('m-d'), 
                    $today->copy()->addDay()->format('m-d')
                ]);
            })
            ->with('profile')
            ->get()
            ->map(function ($emp) use ($today) {
                $post = WallPost::where('business_id', $emp->business_id)
                    ->where('type', 'birthday')
                    ->where('target_employee_id', $emp->id)
                    ->whereYear('created_at', $today->year)
                    ->first();
                $emp->is_posted = $post ? true : false;
                $emp->post_id = $post ? $post->id : null;
                $emp->event_date = $emp->profile->dob;
                return $emp;
            });

        // 2. Work Anniversaries (Today & Tomorrow, where year < current)
        $anniversaries = Employee::where('business_id', $businessId)
            ->whereNotNull('joining_date')
            ->whereYear('joining_date', '<', $today->year)
            ->whereRaw('DATE_FORMAT(joining_date, "%m-%d") IN (?, ?)', [
                $today->format('m-d'), 
                $today->copy()->addDay()->format('m-d')
            ])
            ->get()
            ->map(function ($emp) use ($today) {
                $post = WallPost::where('business_id', $emp->business_id)
                    ->where('type', 'anniversary')
                    ->where('target_employee_id', $emp->id)
                    ->whereYear('created_at', $today->year)
                    ->first();
                $emp->is_posted = $post ? true : false;
                $emp->post_id = $post ? $post->id : null;
                $emp->event_date = $emp->joining_date;
                return $emp;
            });

        // 3. New Joinees (Today or yesterday)
        $joinees = Employee::where('business_id', $businessId)
            ->whereNotNull('joining_date')
            ->whereIn('joining_date', [$today->toDateString(), $today->copy()->subDay()->toDateString()])
            ->get()
            ->map(function ($emp) use ($today) {
                $post = WallPost::where('business_id', $emp->business_id)
                    ->where('type', 'new_joinee')
                    ->where('target_employee_id', $emp->id)
                    ->first();
                $emp->is_posted = $post ? true : false;
                $emp->post_id = $post ? $post->id : null;
                $emp->event_date = $emp->joining_date;
                return $emp;
            });

        // 4. General Posts
        $generalPosts = WallPost::where('business_id', $businessId)
            ->where('type', 'general')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wall.index', compact('birthdays', 'anniversaries', 'joinees', 'generalPosts'));
    }

    public function destroyEventPost($id)
    {
        $post = WallPost::where('id', $id)
            ->where('business_id', Auth::user()->active_business_id)
            ->firstOrFail();
            
        $post->delete();

        return redirect()->back()->with('success', 'Post removed from the Wall successfully.');
    }

    public function storeEventPost(Request $request)
    {
        $request->validate([
            'target_employee_id' => 'nullable|exists:employees,id',
            'type' => 'required|in:birthday,anniversary,new_joinee,general',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from'
        ]);

        $businessId = Auth::user()->active_business_id;

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('wall_images', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        WallPost::create([
            'business_id' => $businessId,
            'employee_id' => null, // System/Admin author
            'target_employee_id' => $request->target_employee_id,
            'type' => $request->type,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to
        ]);

        return redirect()->back()->with('success', 'Event successfully posted to the Wall!');
    }
}
