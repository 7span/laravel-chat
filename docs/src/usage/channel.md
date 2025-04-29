# Usage

The Channel module provides functionality for managing chat channels in your Laravel application. This guide covers all available channel operations and their usage.

## Methods
Laravel Chat allows you to create, retrieve, update, delete, and manage chat channels for user conversations.

### List
---

Retrieves a list of channels for a specific user.

```php
use SevenSpan\Chat\Facades\Channel;

// Parameters:
// - userId (int, Optional): The ID of the user
// - channelIds (array, Optional): Array of specific channel IDs to filter
// - perPage (int, Optional): Number of items per page for pagination

$channels = Channel::list($userId, $channelIds, $perPage);
```

### Detail
---

Retrieves details of a specific channel.

```php
use SevenSpan\Chat\Facades\Channel;

// Parameters:
// - userId (int, Required): The ID of the user requesting the details
// - channelId (int, Required): The ID of the channel

$channelDetails = Channel::detail($userId, $channelId);
```

### Create
---

Creates a new chat channel between two users.

```php
use SevenSpan\Chat\Facades\Channel;

// Parameters:
// - userId (int, Required): The ID of the user creating the channel
// - receiverId (int, Required): The ID of the user to chat with
// - channelName (string, Required): The name of the channel

$result = Channel::create($userId, $receiverId, $channelName);
```

### Update
---

Updates an existing channel's details.

```php
use SevenSpan\Chat\Facades\Channel;

// Parameters:
// - userId (int, Required): The ID of the user updating the channel
// - channelId (int, Required): The ID of the channel to update
// - channelName (string, Required): The new name for the channel

$result = Channel::update($userId, $channelId, $channelName);
```

### Delete
---

Deletes a channel and all its messages.

```php
use SevenSpan\Chat\Facades\Channel;

// Parameters:
// - userId (int, Required): The ID of the user deleting the channel
// - channelId (int, Required): The ID of the channel to delete

$result = Channel::delete($userId, $channelId);
```

### Clear Messages
---

Clears all messages in a channel without deleting the channel itself.

```php
use SevenSpan\Chat\Facades\Channel;

// Parameters:
// - userId (int, Required): The ID of the user clearing the messages
// - channelId (int, Required): The ID of the channel to clear

$result = Channel::clearMessages($userId, $channelId);
```

## Example Usage

```php
// Get all channels for user with ID 1
$userChannels = Channel::list(1);

// Create a new channel
$newChannel = Channel::create(1, 2, 'Support Chat');

// Update a channel name
$updateResult = Channel::update(1, 5, 'Updated Channel Name');

// Delete a channel
$deleteResult = Channel::delete(1, 5);
```