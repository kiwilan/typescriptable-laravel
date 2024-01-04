<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_id',
        'gender',
        'name',
        'original_name',
        'popularity',
        'poster',
        'poster_color',
        'poster_tmdb',
        'tmdb_url',
    ];

    protected $casts = [
        'popularity' => 'float',
    ];

    protected $mediables = [
        'poster',
    ];

    protected $appends = [
        'poster_url',
        'show_route',
    ];

    public function getPosterUrlAttribute(): string
    {
        return $this->mediable('poster');
    }

    public function getShowRouteAttribute(): string
    {
        return route('members.show', ['member_tmdb_id' => $this->tmdb_id]);
    }

    public function memberable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function movies(): MorphToMany
    {
        return $this->morphedByMany(Movie::class, 'memberable');
    }
}
