<?php

namespace App\Core\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

trait ImageTrait {

    public function fileUpload($request, $fieldname, $directory, $fieldremove) {

        $fieldremove = $request->input($fieldremove);

        if($request->hasFile($fieldname) ) {

            $file = $request->file($fieldname);

            if ($fieldremove != '') {
                Storage::disk('public')->delete($fieldremove);
            }
            if(!File::isDirectory($directory)){
                File::makeDirectory(($directory), 0777, true, true);
            }
            $path = Storage::disk('public')->putFile($directory,  $file, 'public');

            $filepath = $path;
        }
        else
        {
            $filepath =  $fieldremove;
        }

        return $filepath;

    }

}
