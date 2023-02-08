<?php

namespace Tests\Data\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    protected array $mediables = ['avatar'];

    protected $fillable = [
        'name',
        'avatar',
    ];

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }
}
