<?php

namespace App\Http\Controllers\Image;

use App\Http\Controllers\Controller;
use App\Models\Image as ImageModel;
use Illuminate\Http\Request;

use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function index(){
        $images = ImageModel::all();
        return view('welcome',compact('images'));
    }

    public function create(Request $request){
        // Validate your data
        $request->validate([
            'title' => 'required|string|unique:"images"',
            'image' => 'required|mimes:jpg,jpeg,png',
        ]);
        
        //assign image to $image variable
        $image = $request->image;

        // rename the image
        $name = $request->title.'_'.time().'.'.$image->getClientOriginalExtension();
        
        $img = Image::make($image->getRealPath());
        $img->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(public_path('images/').$name);

        ImageModel::create([
            'title' => $request->title,
            'name' => $name,
            'url' => 'images/'.$name,
        ]);        

        return redirect()->back();
    }
}
