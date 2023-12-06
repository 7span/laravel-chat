<?php

namespace SevenSpan\Chat\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Channel extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'channels';

    public $fillable = [
        'name',
        'slug',
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

    public function channelUsers()
    {
        return $this->hasMany(ChannelUser::class);
    }

    protected $relationship = [];

    protected $scopedFilters = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
