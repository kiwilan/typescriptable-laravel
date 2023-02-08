<?php

namespace Tests\Data\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'abstract',
        'original_link',
        'picture',
    ];

    protected $appends = [
        'time_to_read',
    ];

    protected $withCount = [
        'chapters',
    ];

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
}
