# Configuration

This guide helps you set up and customize Laravel Chat to fit your application's needs.

## Setup Guide

### Publish config and Migration Files

First, publish the config and migration files using:

```bash
php artisan vendor:publish --provider="SevenSpan\Chat\Providers\ChatServiceProvider"
```

This will publish:
- The `chat.php` config file to your `config/` directory
- Database migration files to your `database/migrations/` directory
---

### Clear Config Cache (If Needed)

If your app has cached config, make sure to clear it so changes take effect:

```bash
php artisan optimize:clear
```
---

### Run Migrations

Now run the migrations to create the necessary database tables:

```bash
php artisan migrate
```
---

## Configuration

After publishing, you can customize the behavior of Laravel Chat inside the `config/chat.php` file.

### Media Directory

Define where uploaded chat media files (like images) should be stored:

```php
'media_folder' => env('CHAT_MEDIA_FOLDER', 'image'),
```

- **Default**: `image`
- **Custom**: Set a different folder by adding to your `.env` file:

```
CHAT_MEDIA_FOLDER=your-folder-name
```
---

### Pusher Event Trigger

Control whether to trigger a Pusher event when a message is sent:

```php
'pusher_event_trigger' => [
    'send_message' => env('CHAT_SEND_MESSAGE_PUSHER_EVENT', true),
],
```

- **Default**: `true` (Pusher event is triggered)
- **To disable**: Add this to your `.env` file:

```
CHAT_SEND_MESSAGE_PUSHER_EVENT=false
```
---

### Message Encryption

Laravel Chat can encrypt message contents for better security:

```php
'encrypt_message' => env('CHAT_ENCRYPT_MESSAGE', false),
```

- **Default**: `false` (encryption disabled)
- **To enable encryption**: Add this to your `.env` file:

```
CHAT_ENCRYPT_MESSAGE=true
```

When enabled, all message will be securely encrypted before storing them in the database.