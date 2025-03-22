<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model;

use Kiwilan\Typescriptable\Typed\Database\DatabaseConversion;

/**
 * Represents a model attribute from database column.
 * Can be an accessor without database column.
 */
class SchemaModelAttribute
{
    public function __construct(
        protected string $name,
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
    ) {}

    /**
     * Make a new instance of `SchemaModelAttribute` to get PHP type and Typescript type
     * from database column (or Laravel `cast`).
     *
     * @param  string  $driver  Database driver, like `mysql` or `sqlite`.
     * @param  array<string, mixed>  $data  Attribute data, from raw array or `SchemaModelAttribute`.
     */
    public static function make(string $driver, array|SchemaModelAttribute $data): self
    {
        // Already `SchemaModelAttribute`
        if ($data instanceof self) {
            $types = DatabaseConversion::make($driver, $data->databaseType, $data->cast);
            $data->phpType = $types->getPhpType();
            $data->typescriptType = $types->getTypescriptType();

            return $data;
        }

        // Raw array
        $self = new self(
            $data['name'] ?? null,
            $data['type'] ?? null,
            $data['increments'] ?? null,
            $data['nullable'] ?? true,
            $data['default'] ?? null,
            $data['unique'] ?? false,
            $data['fillable'] ?? false,
            $data['hidden'] ?? false,
            $data['appended'] ?? false,
            $data['cast'] ?? null,
        );

        if ($self->default === 'NULL') {
            $self->default = null;
        }

        $types = DatabaseConversion::make($driver, $self->databaseType, $self->cast);
        $self->phpType = $types->getPhpType();
        $self->typescriptType = $types->getPhpType();

        return $self;
    }

    /**
     * Get attribute name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get database raw type.
     */
    public function getDatabaseType(): ?string
    {
        return $this->databaseType;
    }

    /**
     * Know if database column has auto-increment.
     */
    public function isIncrements(): bool
    {
        return $this->increments ?? false;
    }

    /**
     * Know if database column is nullable.
     */
    public function isNullable(): bool
    {
        return $this->nullable ?? false;
    }

    /**
     * Get database colum default value.
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * Know if database column has unique value.
     */
    public function isUnique(): bool
    {
        return $this->unique ?? false;
    }

    /**
     * Know if Laravel model's attribute is `fillable`.
     */
    public function isFillable(): bool
    {
        return $this->fillable ?? false;
    }

    /**
     * Set `fillable` property.
     */
    public function setFillable(bool $fillable): self
    {
        $this->fillable = $fillable;

        return $this;
    }

    /**
     * Know if Laravel model's attribute is `hidden`.
     */
    public function isHidden(): bool
    {
        return $this->hidden ?? false;
    }

    /**
     * Set attribute `hidden`.
     */
    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Know if Laravel model's attribute is `appended`.
     */
    public function isAppended(): bool
    {
        return $this->appended ?? false;
    }

    /**
     * Set attribute `appended`.
     */
    public function setAppended(bool $appended): self
    {
        $this->appended = $appended;

        return $this;
    }

    /**
     * Get Laravel cast type, if any.
     */
    public function getCast(): ?string
    {
        return $this->cast;
    }

    /**
     * Set attribute `cast`.
     */
    public function setCast(string $cast): self
    {
        $this->cast = $cast;

        return $this;
    }

    /**
     * Get PHP type.
     */
    public function getPhpType(): ?string
    {
        return $this->phpType;
    }

    /**
     * Set attribute `phpType`.
     */
    public function setPhpType(string $phpType): self
    {
        $this->phpType = $phpType;

        return $this;
    }

    /**
     * Get Typescript type.
     */
    public function getTypescriptType(): ?string
    {
        return $this->typescriptType;
    }

    /**
     * Set attribute `typescriptType`.
     */
    public function setTypescriptType(string $typescriptType): self
    {
        $this->typescriptType = $typescriptType;

        return $this;
    }
}
