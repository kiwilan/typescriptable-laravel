<?php

namespace Kiwilan\Typescriptable\Tests\Data\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'url',
        'content',

        'is_approved',
        'approved_at',
        'rejected_at',

        'commentable_type',
        'commentable_id',
        'comment_id',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected $dates = [
        'approved_at',
        'rejected_at',
    ];

    protected $appends = [
        'gravatar',
    ];

    public function getGravatarAttribute(): string
    {
        $hash = md5(strtolower(trim($this->email)));

        return "https://www.gravatar.com/avatar/{$hash}";
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
