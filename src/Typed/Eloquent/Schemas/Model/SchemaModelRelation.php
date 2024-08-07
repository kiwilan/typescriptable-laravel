<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model;

class SchemaModelRelation
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

    public static function make(array $data): self
    {
        $self = new self(
            $data['name'] ?? null,
            $data['type'] ?? null,
            $data['related'] ?? null,
        );
        $self->snakeCaseName = $self->toSnakeCaseName($self->name);
        $self->isMany = $self->relationTypeisMany($self->laravelType);
        $self->phpType = $self->relatedToModel;
        if ($self->isMany) {
            $self->phpType = "{$self->relatedToModel}[]";
        }

        return $self;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function laravelType(): ?string
    {
        return $this->laravelType;
    }

    public function relatedToModel(): ?string
    {
        return $this->relatedToModel;
    }

    public function snakeCaseName(): string
    {
        return $this->snakeCaseName;
    }

    public function isInternal(): bool
    {
        return $this->isInternal;
    }

    public function isMany(): bool
    {
        return $this->isMany;
    }

    public function phpType(): string
    {
        return $this->phpType;
    }

    public function setPhpType(string $phpType): self
    {
        $this->phpType = $phpType;
        if ($this->isMany) {
            $this->phpType = "{$phpType}[]";
        }

        return $this;
    }

    public function typescriptType(): string
    {
        return $this->typescriptType;
    }

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

        if ($this->isMany) {
            $this->typescriptType = "{$this->typescriptType}[]";
        }

        return $this;
    }

    private function toSnakeCaseName(string $string): string
    {
        $string = preg_replace('/\s+/', '', $string);
        $string = preg_replace('/(?<!^)[A-Z]/', '_$0', $string);
        $string = strtolower($string);

        return $string;
    }

    private function relationTypeisMany(string $type): bool
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
