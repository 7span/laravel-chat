# Laravel Chat

**The Laravel Chat package simplifies one-to-one and group chat integration, facilitates document sharing within chats, manages read and unread message counts, and supports document uploads to both local and AWS S3 storage**

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

## <span id="credits">Credits</span>

- [Harshil Patanvadiya](https://github.com/harshil-patanvadiya)
- [Kajal Pandya](https://github.com/kajal98)
- [Hemratna Bhimani](https://github.com/hemratna)

## <span id="contributing">Contributing</span>

We welcome contributions from the community to improve and enhance this package. If you'd like to contribute, please follow our contribution guidelines.

## <span id="license">License</span>

This package is open-source software licensed under the MIT License. Feel free to use, modify, and distribute it according to the terms of the license.
