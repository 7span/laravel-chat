# Usage

The Message module provides functionality for managing chat messages in your Laravel application. This documentation covers both basic text messages and file attachments.

## Methods 

Laravel Chat allows you to send, retrieve, update, and manage basic text messages in your channels.

### List

Retrieve messages from a specific channel:

```php
use SevenSpan\Chat\Facades\Message;

// Parameters:
// - userId (int, Required): The ID of the user requesting messages
// - channelId (int, Required): The ID of the channel
// - perPage (int, Optional): Number of messages per page

$messages = Message::list($userId, $channelId, $perPage);
```

### Send

Send a text message to a channel:

```php
use SevenSpan\Chat\Facades\Message;

// Parameters:
// - userId (int, Required): The ID of the user sending the message
// - channelId (int, Required): The ID of the channel
// - data (array, Required): Message data containing 'body'
// - variables (array, Optional): Additional message variables

$data = [
    'body' => 'Hello, this is a test message'
];

$variables = [
    ['key' => 'priority', 'meta' => 'high']
];

$result = Message::send($userId, $channelId, $data, $variables);
```

### Update

Update an existing message's content:

```php
use SevenSpan\Chat\Facades\Message;

// Parameters:
// - userId (int, Required): The ID of the user updating the message
// - channelId (int, Required): The ID of the channel
// - messageId (int, Required): The ID of the message to update
// - data (array, Required): Updated message data
// - variables (array, Optional): Updated message variables

$data = [
    'body' => 'Updated message text'
];

$result = Message::update($userId, $channelId, $messageId, $data);
```

### Delete

Delete a message from a channel:

```php
use SevenSpan\Chat\Facades\Message;

// Parameters:
// - userId (int, Required): The ID of the user deleting the message
// - channelId (int, Required): The ID of the channel
// - messageId (int, Required): The ID of the message to delete

$result = Message::delete($userId, $channelId, $messageId);
```

### Read

Mark a specific message and all previous unread messages as read:

```php
use SevenSpan\Chat\Facades\Message;

// Parameters:
// - userId (int, Required): The ID of the user marking messages as read
// - channelId (int, Required): The ID of the channel
// - messageId (int, Required): The ID of the message up to which to mark as read

$result = Message::read($userId, $channelId, $messageId);
```

Mark all messages in a channel as read:

```php
use SevenSpan\Chat\Facades\Message;

// Parameters:
// - userId (int, Required): The ID of the user marking messages as read
// - channelId (int, Required): The ID of the channel

$result = Message::readAll($userId, $channelId);
```

## File

Laravel Chat allows you to send and retrieve file attachments like images and documents.

### Sending File

Send a file message to a channel:

```php
use SevenSpan\Chat\Facades\Message;

// Parameters:
// - userId (int, Required): The ID of the user sending the message
// - channelId (int, Required): The ID of the channel
// - data (array, Required): Message data containing 'file'
// - variables (array, Optional): Additional message variables

$data = [
    'file' => $request->file('attachment')
];

$result = Message::send($userId, $channelId, $data);
```

You can also send a file with text:

```php
$data = [
    'body' => 'Check out this file',
    'file' => $request->file('attachment')
];

$result = Message::send($userId, $channelId, $data);
```

### Retrieving Files

Get a list of files shared in a channel:

```php
use SevenSpan\Chat\Facades\Message;

// Parameters:
// - userId (int, Required): The ID of the user requesting files
// - channelId (int, Required): The ID of the channel
// - type (string, Optional): Type of files to retrieve ('image' or 'zip', default: 'image')
// - perPage (int, Optional): Number of items per page

// Get images
$images = Message::getFiles($userId, $channelId, 'image', $perPage);

// Get documents
$documents = Message::getFiles($userId, $channelId, 'zip', $perPage);
```

### Updating File

Update a file message in a channel:

```php
use SevenSpan\Chat\Facades\Message;

$data = [
    'file' => $request->file('new_attachment')
];

$result = Message::update($userId, $channelId, $messageId, $data);
```


## Examples

```php
// List the last 20 messages in a channel
$messages = Message::list(1, 5, 20);

// Send a text message
$result = Message::send(1, 5, ['body' => 'Hello there!']);

// Send a file message
$result = Message::send(1, 5, ['file' => $request->file('attachment')]);

// Mark all messages as read
$result = Message::readAll(1, 5);
```