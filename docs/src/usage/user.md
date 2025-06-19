# Usage

The User module allows you to retrieve a list of users while excluding the current (logged-in) user.

## Method
Laravel Chat allows you to retrieve and serach users for managing chat participants and connections.

### List

Retrieve a list of users:

```php
use SevenSpan\Chat\User;

// Parameters:
// - userId (int, Required): The ID of the user to exclude from the results
// - name (string, Optional): Search users whose names start with this value
// - perPage (int, Optional): Number of users per page for pagination
$users = User::list($userId, $name = null, $perPage = null);
```

### Example

```php
// Get all users except the current user (ID: 1)
$users = $userService->list(1);

// Get users whose names start with "John" (excluding user ID: 1)
$users = $userService->list(1, 'John');

// Get paginated results (10 users per page)
$users = $userService->list(1, null, 10);

```
