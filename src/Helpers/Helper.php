<?php

declare(strict_types=1);

namespace SevenSpan\Chat\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;

class Helper
{
    public static function fileUpload($file)
    {
        $disk = env("FILESYSTEM_DISK");
        $path = config('chat.media_folder');
        $filename = Str::uuid() . '.' . $file->extension();
        $size = $file->getSize();
        $mimeType = $file->getClientMimeType();

        if ($disk == 'local') {
            $file->storeAs($path, $filename, 'public');
        } elseif ($disk == 's3') {
            $uploadFiles3 = Storage::disk('s3')->putFileAs($path, $file, $filename, 'public');

            if (!$uploadFiles3) {
                $data['errors']['message'][] = 'File is not uploaded into the S3 bucket, Please check you AWS S3 configuration.';
                return $data;
            }
            $disk = env('AWS_BUCKET') . '.s3.ap-south-1.amazonaws.com';
        } else {
            $data['errors']['message'][] = 'File system disk not supported into laravel chat package.';
            return $data;
        }

        $data = [
            'disk' => $disk,
            'path' => $path,
            'filename' => $filename,
            'size' => $size,
            'mime_type' => $mimeType,
            'type' => 'zip'
        ];

        $allowedImageMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($mimeType, $allowedImageMimeTypes)) {
            $data['type'] = 'image';
        }

        return $data;
    }

    public static function fileDelete($disk, $path, $fileName)
    {
        if ($disk == 'local') {
            $file = public_path('storage/' . $path . '/' . $fileName);
            if (File::exists($file)) {
                unlink($file);
                return true;
            }
        } elseif ($disk != 'local') {
            Storage::disk('s3')->delete($path . '/' . $fileName);
            return true;
        }
    }

    public static function isEncrypted($value)
    {
        try {
            Crypt::decryptString($value);
            return true;
        } catch (DecryptException $e) {
            return false;
        }
    }
}
