<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request, Profile $profile)
    {
//        dd($profile->user->user_type);
        $this->validate($request, [
            'gender' => 'required',
            'joining_date' => 'required',
            'nid' => 'nullable|unique:profiles,nid,'. $profile->id,
            'address' => 'required',
        ]);

        $profile_data = Profile::where('id',$profile->id)->first();
        $profile_data->gender = $request->gender;
        $profile_data->nid = $request->nid;
        $profile_data->contact_no1 = $request->contact_no1;
        $profile_data->contact_no2 = $request->contact_no2;
        $profile_data->address = $request->address;
//        $profile_data->address_line2 = $request->address_line2;
        $profile_data->joining_date = date('Y-m-d 00:00:01', strtotime($request->joining_date));
        $profile_data->date_of_birth = ($request->date_of_birth!=null) ? date('Y-m-d', strtotime($request->date_of_birth)) : null;
        $profile_data->company_name_id = $request->company_name_id;
        $profile_data->save ();

        \Session::flash('flash_message','Successfully Updated');
        return redirect('user/' . $profile->user->id);
    }

}
