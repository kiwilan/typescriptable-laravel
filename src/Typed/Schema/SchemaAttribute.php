<?php

namespace Kiwilan\Typescriptable\Typed\Schema;

use Kiwilan\Typescriptable\Typed\Database\DatabaseConversion;

/**
 * A `SchemaAttribute` represents a Laravel model attribute, like `title` for `App\Models\Movie` (a `SchemaModel`).
 *
 * It contains database information (driver, type, default, etc.).
 * It also contains PHP and TypeScript types.
 */
class SchemaAttribute
{
    public function __construct(
        protected string $name,
        protected string $driver = 'mysql',
        protected ?string $databaseType = null,
        protected ?bool $increments = false,
        protected ?bool $nullable = false,
        protected mixed $default = null,
        protected ?bool $unique = false,
        protected ?bool $fillable = false,
        protected ?bool $hidden = false,
        protected ?bool $appended = null,
        protected ?string $cast = null,
        protected ?string $phpType = null,
        protected ?string $typescriptType = null,
        protected ?array $databaseFields = null,
    ) {
        if ($default === 'NULL') {
            $this->default = null;
        }
    }

    public function update(self $self): self
    {
        $this->driver = $self->driver;
        $this->name = $self->name;
        $this->databaseType = $self->databaseType;
        $this->increments = $self->increments;
        $this->nullable = $self->nullable;
        $this->default = $self->default;
        $this->unique = $self->unique;
        $this->fillable = $self->fillable;
        $this->hidden = $self->hidden;
        $this->appended = $self->appended;
        $this->cast = $self->cast;

        $this->handleTypes();

        return $this;
    }

    public function handleTypes(): self
    {
        $conversion = DatabaseConversion::make($this->driver, $this->databaseType, $this->cast);
        $this->phpType = $conversion->getPhpType();
        $this->typescriptType = $conversion->getTypescriptType();

        return $this;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDatabaseType(): ?string
    {
        return $this->databaseType;
    }

    public function isIncremental(): bool
    {
        return $this->increments ?? false;
    }

    public function isNullable(): bool
    {
        return $this->nullable ?? false;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    public function isUnique(): bool
    {
        return $this->unique ?? false;
    }

    public function isFillable(): bool
    {
        return $this->fillable ?? false;
    }

    public function setFillable(bool $fillable): self
    {
        $this->fillable = $fillable;

        return $this;
    }

    public function isHidden(): bool
    {
        return $this->hidden ?? false;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function isAppended(): bool
    {
        return $this->appended ?? false;
    }

    public function setAppended(bool $appended): self
    {
        $this->appended = $appended;

        return $this;
    }

    public function getCast(): ?string
    {
        return $this->cast;
    }

    public function setCast(string $cast): self
    {
        $this->cast = $cast;

        return $this;
    }

    public function getPhpType(): ?string
    {
        return $this->phpType;
    }

    public function setPhpType(string $phpType): self
    {
        $this->phpType = $phpType;

        return $this;
    }

    public function getTypescriptType(): ?string
    {
        return $this->typescriptType;
    }

    public function setTypescriptType(string $typescriptType): self
    {
        $this->typescriptType = $typescriptType;

        return $this;
    }

    public function getDatabaseFields(): array
    {
        return $this->databaseFields;
    }
}
