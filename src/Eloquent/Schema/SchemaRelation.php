<?php

namespace Kiwilan\Typescriptable\Eloquent\Schema;

/**
 * A `SchemaRelation` contains information about a Laravel relation.
 * It contains the relation name, type, related model, and other information.
 */
class SchemaRelation
{
    protected function __construct(
        protected string $name,
        protected ?string $laravelType = null,
        protected ?string $relatedToModel = null,
        protected ?string $snakeCaseName = null,
        protected bool $isInternal = true,
        protected bool $isMany = false,
        protected string $phpType = 'mixed',
        protected string $typescriptType = 'any',
    ) {}

    /**
     * Create a new `SchemaRelation` that contains information about a Laravel relation.
     *
     * @param array{
     *     name?: string,
     *     type?: string,
     *     related?: string,
     * } $data
     *
     * ```php
     * $relation = SchemaRelation::make([
     *    'name' => 'chapters',
     *   'type' => 'HasMany',
     *   'related' => 'App\Models\Chapter',
     * ]);
     * ```
     */
    public static function make(array $data): self
    {
        $self = new self(
            $data['name'] ?? null,
            $data['type'] ?? null,
            $data['related'] ?? null,
        );
        $self->snakeCaseName = $self->toSnakeCaseName($self->name);
        $self->isMany = $self->relationTypeIsMany($self->laravelType);
        $self->phpType = $self->relatedToModel;
        if ($self->isMany) {
            $self->phpType = "{$self->relatedToModel}[]";
        }

        return $self;
    }

    /**
     * Get the name of the relation.
     *
     * E.g. `chapters`, `category`, etc.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the Laravel type of the relation.
     *
     * E.g. `HasMany`, `BelongsTo`, etc.
     */
    public function getLaravelType(): ?string
    {
        return $this->laravelType;
    }

    /**
     * Get the related model of the relation.
     *
     * E.g. `App\Models\Chapter`
     */
    public function getRelatedToModel(): ?string
    {
        return $this->relatedToModel;
    }

    /**
     * Get the snake case name of the relation.
     *
     * E.g. `chapters`, `category`, etc.
     */
    public function getSnakeCaseName(): string
    {
        return $this->snakeCaseName;
    }

    /**
     * Check if the relation is internal.
     *
     * E.g. `App\Models\Chapter` is internal, `Spatie\MediaLibrary\MediaCollections\Models\Media` is not.
     */
    public function isInternal(): bool
    {
        return $this->isInternal;
    }

    /**
     * Check if the relation is many.
     *
     * E.g. `HasMany`, `BelongsToMany`, etc.
     */
    public function isMany(): bool
    {
        return $this->isMany;
    }

    /**
     * Get the PHP type of the relation.
     *
     * E.g. `App\Models\Chapter[]`
     */
    public function getPhpType(): string
    {
        return $this->phpType;
    }

    /**
     * Set the PHP type of the relation.
     *
     * If the relation is many, it will be set to `App\Models\Chapter[]`.
     */
    public function setPhpType(string $phpType): self
    {
        $this->phpType = $phpType;
        if ($this->isMany && ! str_contains($phpType, '[]')) {
            $this->phpType = "{$phpType}[]";
        }

        return $this;
    }

    /**
     * Get the TypeScript type of the relation.
     *
     * E.g. `App.Models.Chapter[]`
     */
    public function getTypescriptType(): string
    {
        return $this->typescriptType;
    }

    /**
     * Set the TypeScript type of the relation.
     *
     * If the relation is many, it will be set to `App.Models.Chapter[]`.
     */
    public function setTypescriptType(string $typescriptType, string $baseNamespace): self
    {
        // e.g. `Spatie\MediaLibrary\MediaCollections\Models\Media`
        if (! str_contains($this->relatedToModel, $baseNamespace)) {
            $this->isInternal = false;
        }

        if ($this->isInternal) {
            if ($typescriptType === 'any') {
                $this->typescriptType = 'any';
            } else {
                $this->typescriptType = "App.Models.{$typescriptType}";
            }
        } else {
            $this->typescriptType = $typescriptType;
        }

        if ($this->isMany && ! str_contains($this->typescriptType, '[]')) {
            $this->typescriptType = "{$this->typescriptType}[]";
        }

        return $this;
    }

    /**
     * Convert a string to snake case.
     */
    private function toSnakeCaseName(string $string): string
    {
        $string = preg_replace('/\s+/', '', $string);
        $string = preg_replace('/(?<!^)[A-Z]/', '_$0', $string);
        $string = strtolower($string);

        return $string;
    }

    /**
     * Check if the relation type is many.
     */
    private function relationTypeIsMany(string $type): bool
    {
        if (in_array($type, [
            'BelongsToMany',
            'HasMany',
            'HasManyThrough',
            'MorphMany',
            'MorphToMany',
        ])) {
            return true;
        }

        if (in_array($type, [
            'BelongsTo',
            'HasOne',
            'HasOneOrMany',
            'HasOneThrough',
            'MorphOne',
            'MorphOneOrMany',
            'MorphPivot',
            'MorphTo',
            'Pivot',
        ])) {
            return false;
        }

        return false;
    }
}
