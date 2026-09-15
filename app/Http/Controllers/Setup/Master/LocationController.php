<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Listing
     */
    public function index()
    {
        $locations = Location::withCount('workProfiles')
            ->where('business_id', Auth::user()->active_business_id)
            ->latest()
            ->get();

        return view(
            'admin.setup.locations.index',
            compact('locations')
        );
    }

    /**
     * Create
     */
    public function create()
    {
        return view(
            'admin.setup.locations.create'
        );
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|max:255',
            'state' => 'required|max:255',
        ]);

        Location::create([

            'user_id' =>
                Auth::id(),

            'business_id' =>
                Auth::user()->active_business_id,

            'name' =>
                $request->name,

            'state' =>
                $request->state,

            'site_head' =>
                $request->site_head,

            'deputy_head' =>
                $request->deputy_head,

            'is_default' =>
                $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('locations.index')
            ->with(
                'success',
                'Location Created Successfully.'
            );
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        $location = Location::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        return view(
            'admin.setup.locations.edit',
            compact('location')
        );
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|max:255',
            'state' => 'required|max:255',
        ]);

        $location = Location::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $location->update([

            'name' =>
                $request->name,

            'state' =>
                $request->state,

            'site_head' =>
                $request->site_head,

            'deputy_head' =>
                $request->deputy_head,

            'is_default' =>
                $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('locations.index')
            ->with(
                'success',
                'Location Updated Successfully.'
            );
    }

    /**
     * Delete
     */
    public function destroy($id)
    {
        $location = Location::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $location->delete();

        return redirect()
            ->route('locations.index')
            ->with(
                'success',
                'Location Deleted Successfully.'
            );
    }

    /**
     * Update Map Location via AJAX
     */
    public function updateMapLocation(Request $request, $id)
    {
        $request->validate([
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $location = Location::where('business_id', Auth::user()->active_business_id)->findOrFail($id);

        $location->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Map location updated successfully.',
            'latitude' => $location->latitude,
            'longitude' => $location->longitude
        ]);
    }
}