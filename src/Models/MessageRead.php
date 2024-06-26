<?php

namespace SevenSpan\Chat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageRead extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'user_id',
        'channel_id',
        'message_id',
        'read_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = ['deleted_at'];

    protected function casts(): array
    {
        return [
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
            'deleted_at' => 'timestamp'
        ];
    }
}
