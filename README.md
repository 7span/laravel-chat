# Laravel Chat

The Laravel Chat package streamlines the integrtion of the one to one chat, Send the document into the chat, Manage the read and unread messages count, Allowing the document uploading into the local and AWS s3 bucket.

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
- Composer
- Laravel >= 8.1
- AWS API credentials (Optional)

## <span id="features">**Features**</span>

- Create the channel of the one to one chat.
- Send the text message and document.
- Filter the documents and text messages.
- Channel read and unread message count.
- Delete the single message and clear the all chat history.
- Allowing the update the channel
- Delete the channels.
- Filter the user names

## <span id="installation">**Installation**</span>

To install this package, use Composer:

```bash
composer require sevenspan/laravel-chat
```

## <span id="configurations">**Configurations**</span>

To configure the package, publish the migration file with the following command:

```bash
php artisan vendor:publish --provider="SevenSpan\LaravelChat\Providers\ChatServiceProvider"
```

This command will publish the configuration file to your project's `config` directories, respectively.

There will be four configuration files when you publish using this command:

1. **[chat.php]**: Contains general configurations.

If you have cached configurations locally, clear the config cache using one of these commands:

```
php artisan optimize:clear
```

After publishing the migration and configuring, create the required tables for this package by running:

```
php artisan migrate
```

## <span id="credits">Credits</span>

- [Hemratna Bhimani](https://github.com/hemratna)
- [Kajal Pandya](https://github.com/kajal98)
- [Harshil Patanvadiya](https://github.com/harshil-7span)

## <span id="contributing">Contributing</span>

We welcome contributions from the community to improve and enhance this package. If you'd like to contribute, please follow our contribution guidelines.

## <span id="license">License</span>

This package is open-source software licensed under the MIT License. Feel free to use, modify, and distribute it according to the terms of the license.

> [!NOTE]
> Future enhancements may include feature-wise plans and workspace-specific development.
