<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Chat File Folder
    |--------------------------------------------------------------------------
    |
    | Chat file directory
    |
    */
    'media_folder' => env('CHAT_MEDIA_FOLDER', 'image'),
    'pusher_event_trigger' => [
        'send_message' => env('CHAT_SEND_MESSAGE_PUSHER_EVENT', true)
    ],
    'encrypt_message' => env('CHAT_ENCRYPT_MESSAGE', false),
];
