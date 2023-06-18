<?php

namespace Kiwilan\Typescriptable\Tests\Data\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'abstract',
        'original_link',
        'picture',
        'published_at',
        'status',
    ];

    protected $appends = [
        'time_to_read',
    ];

    protected $withCount = [
        'chapters',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'status' => PublishStatusEnum::class,
    ];

    /**
     * @return Attribute<string>
     */
    protected function mainTitle(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    public function getTimeToReadAttribute(): int
    {
        $chapters = $this->chapters()->get();
        $times = array_map(
            fn ($chapter) => $chapter['time_to_read'],
            $chapters->toArray()
        );

        return array_sum($times);
    }

    public function getTimeToReadMinutesAttribute(): int
    {
        return intval($this->time_to_read / 60);
    }

    /**
     * @return string[]
     */
    public function getChaptersListAttribute(): array
    {
        return ['chapter 1', 'chapter 2'];
    }

    /**
     * @return int[]
     */
    public function getChaptersIndexAttribute(): array
    {
        return [1, 2];
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
