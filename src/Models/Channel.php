<?php

namespace SevenSpan\Chat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Channel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'channels';

    public $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public $queryable = [
        'id'
    ];

    public function channelUser()
    {
        return $this->hasMany(ChannelUser::class);
    }

    protected $relationship = [];

    protected $scopedFilters = [];
}
