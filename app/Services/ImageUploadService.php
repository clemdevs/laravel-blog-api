<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService {

    public static function uploadAnImage(Request $request, $object, $property, $method)
    {
        try{
            $file = $request->file(array_keys($_FILES));
            if(!empty($file)){
                if(!$object){
                    //If there isn't an object return an error
                    return response()->json('Resource not found', 404);
                }
                //else save the image.
                self::saveImage($file, $object, $property, $method);
            }
            return response()->json('Something went wrong', 500);
        }
        catch(\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Save an image and update it in the database
     * @param \Illuminate\Database\Eloquent\Model  $model
     */
    private static function saveImage(UploadedFile $file, \Illuminate\Database\Eloquent\Model $model, string $property, string $method = 'POST')
    {

        $filename = $file->getClientOriginalName();
        $filepath = $file->path();
        $image_dir = public_path('images/');
        $dir = 'images/';
        $image_name = $image_dir . $filename;

        //Check if directory exists.
        if(!File::exists($image_dir)){
            //If it does create a new one.
            File::makeDirectory($image_dir, 0777);
        };

        //if file is present generate new filename
        $setNewImageName = time() . '-' . $filename;

        if($method === 'PATCH'){
            if(isset($method[$property])){
                $file = self::getImageName($model, $property);

                if(!File::exists($file)){
                    $model->update([$property => $dir . base64_encode($setNewImageName)]);
                    file_put_contents($image_dir.$setNewImageName, file_get_contents($filepath));
                }
                //update the property with the new filename
                $model->update([$property => $dir . base64_encode($filename)]);
                //remove the old file.
                file_put_contents($image_name, file_get_contents($filepath));
            }
        }

        if($method === 'POST'){

            if(!File::exists($image_name)){
                //update the property with the new filename
                $model->update([$property => $dir . base64_encode($filename)]);
                //remove the old file.
                file_put_contents($image_name, file_get_contents($filepath));
            } else {
                $model->update([$property => $dir . base64_encode($setNewImageName)]);

                file_put_contents($image_dir.$setNewImageName, file_get_contents($filepath));
            }

        }

    }

    public static function getImageName(\Illuminate\Database\Eloquent\Model $model, string $key)
    {
        if(isset($model[$key])){
            return base64_decode(Str::after($model[$key], 'images/'));
        }
    }

}
