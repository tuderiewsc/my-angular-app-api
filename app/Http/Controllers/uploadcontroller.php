<?php

namespace App\Http\Controllers;

use App\imageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class uploadcontroller extends Controller
{
    public function index()
    {
        return view('uploadimage');
    }


    public function uploadImage(Request $request)
    {
        //return dd($request->section);

        $year = Carbon::now()->year;

        foreach ($request->file('images') as $file) {
            $image_path = "\upload\image";
            $file_name = time() . '-' . $year . '-' . $file->getClientOriginalName();
            $file->storeAs($image_path, $file_name, 'public_local');
            // $path = $file->store($image_path, 'public_local');
            $res = imageUploader::create(array_merge($request->all(), ['image' => $file_name]));
        }

        if ($res) {
            $request->session()->flash('uploadSuccess', 'آپلود موفق');
        }

        return redirect()->back();
    }


    public function uploadImagesList($section){
        $imgList = imageUploader::where('section' , $section)->get();
        return $imgList;
    }


//    public function uploadImg(Request $request)
//    {
//
//        $year = Carbon::now()->year;
//        $file = $request->file('images');
//        $image_path = "\upload\image";
//        $file_name = microtime() . '-' . $year . '-' . $file->getClientOriginalName();
//        $file->storeAs($image_path, $file_name, 'public_local');
//        $res = imageUploader::create(array_merge($request->all(), ['image' => $file_name]));
//
//        if ($res) {
//            return $file_name;
//        }else{
//            return response()->json(['result' => 'آپلود ناموفق']);
//        }
//
//    }
}
