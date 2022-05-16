<?php

namespace Core\Traits;

use Facade\FlareClient\Stacktrace\File;

trait FileTrait
{
    public function uploadFile($request, $fileUpload, $pathUpload, $oldFile = "")
    {
        if ($request->hasFile($fileUpload)) {
            if (! \File::isDirectory($pathUpload . 'user' . auth()->user()->id)) {
                $isPath = \File::makeDirectory($pathUpload . 'user' . auth()->user()->id);
                $pathUpload = $isPath ? $pathUpload . 'user' . auth()->user()->id . '/' : '';
            } else {
                $pathUpload = $pathUpload . 'user' . auth()->user()->id . '/';
            }
            $fileExtension = $request->file($fileUpload)->getClientOriginalExtension();
            $fileName = time() . rand() . "." . strtolower($fileExtension);
            $request->file('avatar')->move(public_path($pathUpload), $fileName);
            $this->removeFile(public_path($pathUpload) . $oldFile);
            return $fileName;
        }
        return $oldFile;
    }

    public function uploadIdentityCard($files, $pathUpload, $oldFiles)
    {
        if (! empty($files)) {
            if (! \File::isDirectory($pathUpload . 'user' . auth()->user()->id. '/')) {
                $isPath = \File::makeDirectory($pathUpload . 'user' . auth()->user()->id);
                $pathUpload = $isPath ? $pathUpload . 'user' . auth()->user()->id . '/' : '';
            } else {
                $pathUpload = $pathUpload . 'user' . auth()->user()->id . '/';
            }
            $images = [];
            foreach ($files[0] as $key => $file) {
                
                $fileExtension = $file->getClientOriginalExtension();
                $fileName = time() . rand() . "." . strtolower($fileExtension);
                $file->move(public_path($pathUpload), $fileName);
                foreach ($oldFiles as $oldFile) {
                    $this->removeFile(public_path($pathUpload) . $oldFile);
                }
                $images[$key] = $fileName;
            }
            return $images;
            
        }
        return null;
    }

    public function removeFile($pathImg)
    {
        if (is_file($pathImg)) {
            unlink($pathImg);
        }
    }
}
