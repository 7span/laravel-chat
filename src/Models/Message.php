<?php

namespace SevenSpan\Chat\Models;

use App\Models\User;
use SevenSpan\Chat\Helpers\Helper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['file_url'];

    public $fillable = [
        'sender_id',
        'channel_id',
        'body',
        'disk',
        'path',
        'filename',
        'size',
        'mime_type',
        'type',
        'created_by',
        'updated_by',
        'deleted_by'
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

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute()
    {
        $url = null;
        if ($this->type != 'text' && $this->disk != null) {
            if ($this->disk == 'local') {
                $url = Storage::disk('public')->url($this->path . '/' . $this->filename);
            } elseif ($this->disk != 'local') {
                $url = 'https://' . $this->disk . '/' . $this->path . '/' . $this->filename;
            }
        }

        return $url;
    }

    public function variables()
    {
        return $this->hasMany(MessageVariable::class, 'message_id', 'id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        if(config('chat.encrypt_message')) {
            return [
                'body' => 'encrypted',
            ];
        }else {
            return [
                'body' => 'string',
            ];
        }
    }

   
    protected function body(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                return Helper::isEncrypted($value) ? Crypt::decryptString($value) : $value;
            }
        );
    }
}
