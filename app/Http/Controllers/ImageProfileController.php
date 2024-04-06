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
        $utime = round(microtime(true) * 1000);
        if ($request->image_type=='sign') {
            $this->validate($request, [
                'sign' => 'mimes:png,jpeg,jpg,bmp | required | max:200'
            ]);
            $sign = $request->file('sign');

            $slug = 'dg';
            $profile_sign = ImageProfile::find($id);
            if (isset($sign)) {
//            make unique name for sign
                $imagename = $slug . '-' . $utime . '.' . $sign->getClientOriginalExtension();
//            check category dir is exists
                if (!Storage::disk('public')->exists('sign')) {
                    Storage::disk('public')->makeDirectory('sign');
                }
//            delete old sign
                if (Storage::disk('public')->exists('sign/' . $profile_sign->sign)) {
                    Storage::disk('public')->delete('sign/' . $profile_sign->sign);
                }
//            resize sign for category and upload
                $profile_image = Image::make($sign)->resize(210, 70)->stream();
                Storage::disk('public')->put('sign/' . $imagename, $profile_image);
            } else {
                $imagename = $profile_sign->sign;
            }
            $profile_sign->sign = $imagename;
            $profile_sign->save();
        } else {
            $this->validate($request, [
                'image' => 'mimes:png,jpeg,jpg,bmp | required | max:1024'
            ]);
            $image = $request->file('image');
            $slug = 'ip';
            $profile_img = ImageProfile::find($id);
            if (isset($image)) {
//            make unique name for image
                $imagename = $slug . '-' . $utime . '.' . $image->getClientOriginalExtension();
//            check category dir is exists
                if (!Storage::disk('public')->exists('image_profile')) {
                    Storage::disk('public')->makeDirectory('image_profile');
                }
//            delete old image
                if (Storage::disk('public')->exists('image_profile/' . $profile_img->image)) {
                    Storage::disk('public')->delete('image_profile/' . $profile_img->image);
                }
//            resize image for category and upload
                $profile_image = Image::make($image)->resize(300, 300)->stream();
                Storage::disk('public')->put('image_profile/' . $imagename, $profile_image);
            } else {
                $imagename = $profile_img->image;
            }
            $profile_img->image = $imagename;
            $profile_img->save();
        }
        \Session::flash('flash_success', 'Avatar updated successfully');
        return redirect('user/' . $request->user_id.'/edit');
    }

}


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
