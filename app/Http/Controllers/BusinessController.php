<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\UserBusinessSession;
use App\Models\MaternityLeavePolicy;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    // Dashboard Page
    public function index()
{
    $businesses = Business::where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('business_dashboard.index', compact('businesses'));
}

    // Add Main Page
  

    // Add Business Page
public function create()
{
    $json = file_get_contents(
        public_path('assets/admin/json/states-and-districts.json')
    );

    $statesData = json_decode($json, true);

    return view(
        'business_dashboard.add',
        compact('statesData')
    );
}

    // Store Data
    /**

* Store Data
  */
  public function store(Request $request)
  {
  $request->validate([
  'business_name' => 'required|string|max:255',
  'pan_number' => 'nullable|string|max:10',
  'address' => 'nullable|string',
  'city' => 'nullable|string|max:255',
  'pincode' => 'nullable|string|max:10',
  'state' => 'nullable|string|max:255',
  'district' => 'required|string|max:255',
  'business_constitution' => 'nullable|string|max:255',
  ]);

  $business=Business::create([
  'user_id' => Auth::id(),
  'business_name' => $request->business_name,
  'pan_number' => $request->pan_number,
  'address' => $request->address,
  'city' => $request->city,
  'pincode' => $request->pincode,
  'state' => $request->state,
   'district' => $request->district,
  'business_constitution' => $request->business_constitution,
  'current_step' => 1,
  'is_completed' => 0,
  'status' => 'draft',
  ]);

  // Seed default maternity leave policies
  $childTypes = array_keys(MaternityLeavePolicy::CHILD_TYPES);
  foreach ($childTypes as $type) {
      MaternityLeavePolicy::create([
          'business_id' => $business->id,
          'user_id'     => Auth::id(),
          'child_type'  => $type,
          'created_by'  => Auth::id(),
      ]);
  }

  return redirect()
  ->route('business.dashboard')
  ->with('success', 'Business Added Successfully');
  }

/**

* Update Data
  */
 


    // Edit Page
  public function edit($id)
{
    $business = Business::where('user_id', Auth::id())
        ->findOrFail($id);

    $json = file_get_contents(
        public_path('assets/admin/json/states-and-districts.json')
    );

    $statesData = json_decode($json, true);

    return view(
        'business_dashboard.edit',
        compact('business', 'statesData')
    );
}



public function show($id)
{
    $business = Business::where('user_id', Auth::id())
        ->findOrFail($id);

    $userId = Auth::id();

    // Expire previous active business session
    UserBusinessSession::where('user_id', $userId)
        ->where('status', 'ACTIVE')
        ->update([
            'status' => 'EXPIRED'
        ]);

    // Create new active session
    UserBusinessSession::create([
        'user_id'     => $userId,
        'business_id' => $business->id,
        'status'      => 'ACTIVE',
      'ip_address'  => request()->ip(),
    'user_agent'  => request()->userAgent(),
    ]);

    // Store active business in users table (recommended)
    Auth::user()->update([
        'active_business_id' => $business->id
    ]);

    return redirect()->route('root');
}
    // Update Data
  public function update(Request $request, $id)
  {
  $request->validate([
  'business_name' => 'required|string|max:255',
  'pan_number' => 'nullable|string|max:10',
  'address' => 'nullable|string',
  'city' => 'nullable|string|max:255',
  'pincode' => 'nullable|string|max:10',
  'state' => 'nullable|string|max:255',
   'district' => 'required|string|max:255',
  'business_constitution' => 'nullable|string|max:255',
  ]);

  $business = Business::findOrFail($id);

  $business->update([
  'business_name' => $request->business_name,
  'pan_number' => $request->pan_number,
  'address' => $request->address,
  'city' => $request->city,
  'pincode' => $request->pincode,
  'state' => $request->state,
    'district' => $request->district,
  'business_constitution' => $request->business_constitution,
  ]);

  return redirect()
  ->route('business.dashboard')
  ->with('success', 'Business Updated Successfully');
  }

    // Delete Data
   public function destroy($id)
{
    $business = Business::where('user_id', Auth::id())
        ->findOrFail($id);

    $business->delete();

    return redirect()
        ->route('business.dashboard')
        ->with('success', 'Business Deleted Successfully');
}
}