<?php

function dateFormat($date){
    
    return \Carbon\Carbon::parse($date)->format('d-m-Y H:i:s');
}

function getFile($path){

    return "https://isport-india.s3.ap-south-1.amazonaws.com/".$path;
}

function uploadImage($image, $upath = '')
{
    return Storage::disk('s3')->put($upath, $image);

    // $path = ($upath == '') ? 'images/' : $upath;

    // $storepath = Storage::disk('public')->path($path);

    // if (!is_dir($storepath)) {

    //     \File::makeDirectory($storepath, 0777, true);
    // }

    // $imageName = time() . '-' . Str::random(5) . '.' . $image->extension();

    // $image->storeAs('public/' . $path, $imageName);

    // return $path . '/' . $imageName;
}

function getImageUrl($image)
{
    if ($image != null) {

        return Storage::disk('s3')->url($image);
    }
    
    return asset('user.png');
}

function deleteImage($imageUrl)
{
    if ($imageUrl != null) {

        if (Storage::disk('s3')->exists($imageUrl)) {

            Storage::disk('s3')->delete($imageUrl);

            return true;
        }
    }

    return false;
}