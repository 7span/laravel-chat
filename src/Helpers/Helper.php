<?php

declare(strict_types=1);

namespace SevenSpan\LaravelChat\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
}
