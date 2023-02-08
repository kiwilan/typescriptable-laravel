<?php

namespace Tests\Data\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Chapter extends Model
{
    use HasFactory;

    protected $time_to_read_with = 'content';

    protected $fillable = [
        'name',
        'content',
        'number',
    ];

    protected $appends = [
        'title',
        'content_html',
    ];

    // public function title(): Attribute
    // {
    //     $name = $this->name ? ": {$this->name}" : '';

    //     return Attribute::make(
    //         get: fn () => "Chapter {$this->number}{$name}",
    //     );
    // }

    public function getTitleAttribute(): ?string
    {
        // @phpstan-ignore-next-line
        $name = $this->name ? ": {$this->name}" : '';

        // @phpstan-ignore-next-line
        return "Chapter {$this->number}{$name}";
    }

    public function getContentHtmlAttribute(): ?string
    {
        // @phpstan-ignore-next-line
        return Str::markdown($this->content);
    }

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
