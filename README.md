# Laravel Chat

The Laravel Chat package simplifies one-to-one and group chat integration facilitates document sharing within chats, manages read and unread message counts, and supports document uploads to both local and AWS S3 storage

## Index

- [Prerequisites](#prerequisites)
- [Features](#features)
- [Installation](#installation)
- [Configurations](#configurations)
- [Credits](#credits)
- [Contributing](#contributing)
- [License](#license)

## <span id="prerequisites">**Prerequisites**</span>

Before you get started, make sure you have the following prerequisites installed:

- PHP >= 8.1
- Composer >= 2.0
- Laravel >= 8.1
- AWS API credentials (Optional)

## <span id="features">**Features**</span>

- One-to-One Chat Integration
- Group Chat Integration
- Document Sharing within Chats
- Read and Unread Message Count Management
- Document Upload Support for Local Storage
- Document Upload Support for AWS S3 Storage

## <span id="installation">**Installation**</span>

To install this package, use Composer:

```bash
composer require sevenspan/laravel-chat
```

## <span id="configurations">**Configurations**</span>

To configure the package, publish the migration file with the following command:

```bash
php artisan vendor:publish --provider="SevenSpan\Chat\Providers\ChatServiceProvider"
```

This command will publish the configuration file chat.php to your project's config directories, respectively.

If you have cached configurations locally, clear the config cache using one of these commands:

```
php artisan optimize:clear
```

After publishing the migration and configuring, create the required tables for this package by running:

```
php artisan migrate
```

## Usage

Once you have installed the package, you can start using its features in your Laravel application. Here's a brief overview of how to use some of the main features:

``` php
use SevenSpan\Chat\Facades\Channel;
```

### 1. List Channels

Use the `list` method to get all channels.


``` php
use SevenSpan\Chat\Facades\Channel;

// $userId = 12; (Required)
// $perPage = 10; (Optional)

Channel::list($userId, $perPage);
```

### 2. Detail of Channel

Use the `detail` method to get the detail of channel.


``` php
use SevenSpan\Chat\Facades\Channel;

// $userId = 12; (Required)
// $channelId = 10; (Required)

Channel::detail($userId, $channelId);
```

### 3. Create Channel

Use the `create` method to create the new channel.


``` php
use SevenSpan\Chat\Facades\Channel;

// $userId = 12; (Required)
// $receiverId = 10; (Required)
// $channelName = "Goverment project" (Required)

Channel::detail($userId, $receiverId, $channelName);
```


### 4. Update Channel

Use the `update` method to update the channel details.


``` php
use SevenSpan\Chat\Facades\Channel;

// $userId = 12; (Required)
// $receiverId = 10; (Required)
// $channelName = "Goverment project" (Required)

Channel::detail($userId, $receiverId, $channelName);
```

### 5. Delete Channel

Use the `delete` method to delete the channel.


``` php
use SevenSpan\Chat\Facades\Channel;

// $userId = 12; (Required)
// $channelId = 10; (Required)

Channel::delete($userId, $channelId);
```

### 6. Clear Channel History

Use the `clearMessage` method to clear the chat history.

```php
use SevenSpan\Chat\Facades\Channel;

// $userId = 1; (Required)
// $channelId = 1 (Required)

Channel::clearMessage($userId, $channelId);
```

### 7. List Message

Use the `list` method to get all messages of the channel.

```php
use SevenSpan\Chat\Facades\Message;

// $userId = 1; (Required)
// $channelId = 1 (Required)
// $perPage = 10; (Optional)

Message::list($userId, $channelId, $perPage);
```

### 8. Send Message

Use the `send` method to send a message.

```php
use SevenSpan\Chat\Facades\Message;

// $userId = 1; (Required)
// $channelId = 1 (Required)
// $data = [
//    'body' => 'TEXT_MESSAGE',
//    'file' => Image Or Document
// ]; (Required)

Message::send($userId, $channelId, $data);
```

> [!NOTE]
> In the $data param either body or file is required.

### 9. Get Files Message

Use the `getFiles` method to document of the channel.

```php
use SevenSpan\Chat\Facades\Message;

// $userId = 1; (Required)
// $channelId = 1 (Required)
// $type = 'image' (Default: image)
// $perPage = 10; (Optional)

Message::getFiles($userId, $channelId, $type, $perPage);
```

> [!NOTE]
>  $type param supported value is `image` or `zip`.

### 10. Delete Message

Use the `delete` method to delete the message.

```php
use SevenSpan\Chat\Facades\Message;

// $userId = 1; (Required)
// $channelId = 1 (Required)
// $messageId = 10; (Required)

Message::delete($userId, $channelId, $messageId);
```

### 11. Read Message

Use the `read` method to read the message of a channel.

```php
use SevenSpan\Chat\Facades\Message;

// $userId = 1; (Required)
// $channelId = 1 (Required)
// $messageId = 10; (Required)

Message::read($userId, $channelId, $messageId);
```
> [!NOTE]
> The messages that have a lesser value than $messageId will be read automatically.

### 12. User List

Use the `list` method to get a list of users and also search for the name of the user.

```php
use SevenSpan\Chat\Facades\User;

// $userId = 1; (Required)
// $name = "John Doe" (Optional)
// $perPage = 10; (Optional)

User::list($userId, $name, $perPage);
```

## <span id="credits">Credits</span>

- [Harshil Patanvadiya](https://github.com/harshil-patanvadiya)
- [Kajal Pandya](https://github.com/kajal98)
- [Hemratna Bhimani](https://github.com/hemratna)

## <span id="contributing">Contributing</span>

We welcome contributions from the community to improve and enhance this package. If you'd like to contribute, please follow our contribution guidelines.

## <span id="license">License</span>

This package is open-source software licensed under the MIT License. Feel free to use, modify, and distribute it according to the terms of the license.
