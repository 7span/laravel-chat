<?php

namespace SevenSpan\Chat\Models;

use App\Models\User;
use App\Traits\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChannelUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'channel_users';

    public $fillable = [
        'user_id',
        'channel_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];

    protected $hidden = ['deleted_at'];

    public $queryable = [
        'id'
    ];

    protected $relationship = [];

    protected $scopedFilters = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
