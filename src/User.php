<?php

declare(strict_types=1);

namespace SevenSpan\Chat;

use App\Models\User as UserModel;

class User
{
    public function list(int $userId, string $name = null, int $perPage = null)
    {
        $users = UserModel::where('id', '!=', $userId);

        if ($name) {
            $users->where("name", 'LIKE', "{$name}%");
        }

        $users = $perPage ? $users->paginate($perPage) : $users->get();
        return $users;
    }
}
