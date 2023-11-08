<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait ImageTrait
{

    public static function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validated && $request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            return $filename;
        }
    }

    public static function deleteImage(string $disk, string $folder, string $image)
    {
        $path = $folder . '/' . $image;
        switch ($disk) {
            case 'public':
                if (File::exists(public_path($path))) {
                    File::delete(public_path($path));
                }
            break;
            case 'storage':
                if (Storage::exists($path)) {
                    Storage::disk($folder)->delete($image);
                }
            break;
        }
    }
}
