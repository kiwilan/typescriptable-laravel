<?php

namespace Kiwilan\Typescriptable\Eloquent\Schema;

use Kiwilan\Typescriptable\Eloquent\Database\DatabaseConverter;

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

    /**
     * Update the current attribute with another attribute and handle types.
     */
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

    /**
     * Handle types for the attribute to set the PHP and TypeScript types.
     */
    public function handleTypes(): self
    {
        $conversion = DatabaseConverter::make($this->driver, $this->databaseType, $this->cast);
        $this->phpType = $conversion->getPhpType();
        $this->typescriptType = $conversion->getTypescriptType();

        return $this;
    }

    /**
     * Get the driver of attribute.
     *
     * Example: `mysql`
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Get the name of attribute.
     *
     * Example: `title`
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the database type of field.
     *
     * Example: `varchar(255)`
     */
    public function getDatabaseType(): ?string
    {
        return $this->databaseType;
    }

    /**
     * Check if the field is incremental in database.
     */
    public function isIncremental(): bool
    {
        return $this->increments ?? false;
    }

    /**
     * Check if the field is nullable in database.
     */
    public function isNullable(): bool
    {
        return $this->nullable ?? false;
    }

    /**
     * Get the default value of field in database.
     *
     * Example: `true`
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * Check if the field is unique in database.
     */
    public function isUnique(): bool
    {
        return $this->unique ?? false;
    }

    /**
     * Check if the attribute is fillable in model.
     */
    public function isFillable(): bool
    {
        return $this->fillable ?? false;
    }

    /**
     * Set the attribute as fillable in model.
     */
    public function setFillable(bool $fillable): self
    {
        $this->fillable = $fillable;

        return $this;
    }

    /**
     * Check if the attribute is hidden in model.
     */
    public function isHidden(): bool
    {
        return $this->hidden ?? false;
    }

    /**
     * Set the attribute as hidden in model.
     */
    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Check if the attribute is appended in model.
     */
    public function isAppended(): bool
    {
        return $this->appended ?? false;
    }

    /**
     * Set the attribute as appended in model.
     */
    public function setAppended(bool $appended): self
    {
        $this->appended = $appended;

        return $this;
    }

    /**
     * Get the Laravel cast of attribute.
     *
     * Example: `string`
     */
    public function getCast(): ?string
    {
        return $this->cast;
    }

    /**
     * Set the Laravel cast of attribute.
     *
     * Example: `string`
     */
    public function setCast(string $cast): self
    {
        $this->cast = $cast;

        return $this;
    }

    /**
     * Get the PHP type of attribute.
     *
     * Example: `string`
     */
    public function getPhpType(): ?string
    {
        return $this->phpType;
    }

    /**
     * Set the PHP type of attribute.
     *
     * Example: `string`
     */
    public function setPhpType(string $phpType): self
    {
        $this->phpType = $phpType;

        return $this;
    }

    /**
     * Get the TypeScript type of attribute.
     *
     * Example: `string`
     */
    public function getTypescriptType(): ?string
    {
        return $this->typescriptType;
    }

    /**
     * Set the TypeScript type of attribute.
     *
     * Example: `string`
     */
    public function setTypescriptType(string $typescriptType): self
    {
        $this->typescriptType = $typescriptType;

        return $this;
    }

    /**
     * Get the database fields of attribute (depending on the driver).
     *
     * Example: `['Field' => 'title', 'Type' => 'varchar(255)', 'Null' => 'NO', 'Key' => '', 'Default' => null, 'Extra' => '']`
     */
    public function getDatabaseFields(): array
    {
        return $this->databaseFields;
    }
}
