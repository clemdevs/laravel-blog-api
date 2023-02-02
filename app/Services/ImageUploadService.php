<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{

    public static function uploadAnImage(Request $request, $object, $property, $method)
    {
        try {
            $file = $request->file(array_keys($_FILES));
            if (!empty($file)) {
                if (!$object) {
                    //If there isn't an object return an error
                    return response()->json('Resource not found', 404);
                }
                //else save the image.
                self::saveImage($file, $object, $property, $method);
            }
            return response()->json('Something went wrong', 500);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Save an image and update it in the database
     * @param \Illuminate\Database\Eloquent\Model  $model
     */
    private static function saveImage(UploadedFile $file, \Illuminate\Database\Eloquent\Model $model, $property, string $method = 'POST')
    {
        $filename = $file->getClientOriginalName();
        $filepath = $file->path();
        //path to image directory
        $image_dir = public_path('images/');
        //relative path
        $dir = 'images/';
        //absolute path of the file
        $absolute_path = $image_dir . $filename;

        //Check if directory exists.
        if (!File::exists($image_dir)) {
            //If it does create a new one.
            File::makeDirectory($image_dir, 0777); //TODO: permissions 0777 is bad practice
        };

        $setNewImageName = time() . '-' . $filename;

        if ($method === 'POST' || $method === 'PUT') {
            $filename_db = self::getImageName($model, $property);

            if (!File::exists($absolute_path)) {
                $model->update([$property => $dir . base64_encode($filename)]);
                file_put_contents($absolute_path, file_get_contents($filepath));
            } else {
                if ($method !== 'POST') {
                    //Delete the image if it already existed while updating the model and replace it with a new image.
                    if (File::exists($image_dir . $filename_db)) File::delete($image_dir . $filename_db);
                }
                $model->update([$property => $dir . base64_encode($setNewImageName)]);
                file_put_contents($image_dir . $setNewImageName, file_get_contents($filepath));
            }
        }
    }

    public static function getImageName(\Illuminate\Database\Eloquent\Model $model, string $key)
    {
        return base64_decode(Str::after($model[$key], 'images/'));
    }
}
