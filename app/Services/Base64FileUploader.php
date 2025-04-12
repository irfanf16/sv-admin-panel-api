<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Bkwld\Croppa\Facades\Croppa;
class Base64FileUploader
{
    public function handle($base64_image, string $path = 'images', ?string $disk = null, ?string $filenameWithoutExtension = null): string
    {
        if ($disk === null) {
            $disk = config('filesystems.default');
        }
        @list($type, $file_data) = explode(';', $base64_image);
        $parts = explode("/", $type);
        $extension = isset($parts[1]) ? $parts[1] : 'png';
        @list(, $file_data) = explode(',', $file_data); 
        $imageName = time().'-'.Str::random(10).'.'.$extension;   
        Storage::disk($disk)->put($path.'/'. $imageName, base64_decode($file_data));
        return Croppa::url(Storage::url($path.'/'. $imageName));
    }
}
