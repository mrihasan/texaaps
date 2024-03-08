<?php

namespace App\Http\Controllers;

use App\Models\ImageProfile;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ImageProfileController extends Controller
{
    public function update(Request $request, $id)
    {
//        dd($id);
        $this->validate($request,[
//            'name' => 'required',
            'image' => 'mimes:png,jpeg,jpg,bmp | required | max:1024'
        ]);
        // get form image
        $image = $request->file('image');
//        dd($image);
        $slug = 'ip';
        $profile_img = ImageProfile::find($id);
//        dd($profile_img);
        if (isset($image))
        {
//            make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
//            dd($imagename);
//            check category dir is exists
            if (!Storage::disk('public')->exists('image_profile'))
            {
                Storage::disk('public')->makeDirectory('image_profile');
            }
//            delete old image
            if (Storage::disk('public')->exists('image_profile/'.$profile_img->image))
            {
                Storage::disk('public')->delete('image_profile/'.$profile_img->image);
            }
//            resize image for category and upload
            $profile_image = Image::make($image)->resize(300,300)->stream();
            Storage::disk('public')->put('image_profile/'.$imagename,$profile_image);

//            //            check category thumbnails dir is exists
//            if (!Storage::disk('public')->exists('image_profile/thumbnails'))
//            {
//                Storage::disk('public')->makeDirectory('image_profile/thumbnails');
//            }
//            //            delete old thumbnails image
//            if (Storage::disk('public')->exists('image_profile/thumbnails/'.$profile_img->image))
//            {
//                Storage::disk('public')->delete('image_profile/thumbnails/'.$profile_img->image);
//            }
//            //            resize image for category thumbnails and upload
//            $thumbnails = Image::make($image)->resize(60,60)->stream();
//            Storage::disk('public')->put('image_profile/thumbnails/'.$imagename,$thumbnails);

        } else {
            $imagename = $profile_img->image;
        }

//        $profile_img->name = $request->name;
//        $profile_img->slug = $slug;
        $profile_img->image = $imagename;
        $profile_img->save();
//        return redirect('/users/'.$request->user_id.'#tab_1_2');
        \Session::flash('flash_success', 'Avatar updated successfully');
        return redirect('user/'.$request->user_id);
    }

}
