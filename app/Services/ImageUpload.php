<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUpload
{

    /**
     * Uploads the file and returns the file path.
     *
     * @param UploadedFile $image_file
     * @return string
     */
    public static function setImage(UploadedFile $image_file)
    {
        //Retrieve image filename
        $file_name = $image_file->getClientOriginalName();

        //In case filename exist we concatinate it with time followed by underscore
        $new_file_name = time() . '_' . $file_name;

        //File path.
        $file_path = $image_file->path();

        //path to save in the database.
        $dir = 'images/';

        //If the file doesn't exist in the image folder upload  a new one.
        if(!Storage::disk('images')->exists($file_name)){
            Storage::disk('images')->put($file_name, file_get_contents($file_path));
            return $dir.base64_encode($file_name);
        };

        //Otherwise upload a new file with different filename.
        Storage::disk('images')->put($new_file_name, file_get_contents($file_path));
        return $dir.base64_encode($new_file_name);
    }


    /**
     * Delete Image from DB
     *
     * @param Model $model
     * @return void
     */
    public static function deleteImage(Model $model): void
    {
        Storage::disk('images')->delete(self::getImageNameFromDb($model));
    }

    /**
     * Get image from the database and returns decoded filename as string.
     *
     * @param Model $model
     * @return string
     */
    public static function getImageNameFromDb(Model $model)
    {
        return base64_decode(Str::after($model, 'images/'));
    }
}
