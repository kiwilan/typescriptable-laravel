<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model;

use Kiwilan\Typescriptable\Typed\Database\DatabaseConversion;

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
     * Make a new instance.
     *
     * @param  string  $driver  Database driver.
     * @param  array<string, mixed>  $data  Attribute data.
     */
    public static function make(string $driver, array|SchemaModelAttribute $data): self
    {
        if ($data instanceof self) {
            $types = DatabaseConversion::make($driver, $data->databaseType, $data->cast);
            $data->phpType = $types->phpType();
            $data->typescriptType = $types->typescriptType();

            return $data;
        }

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
        $self->phpType = $types->phpType();
        $self->typescriptType = $types->typescriptType();

        return $self;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function databaseType(): ?string
    {
        return $this->databaseType;
    }

    public function increments(): bool
    {
        return $this->increments ?? false;
    }

    public function nullable(): bool
    {
        return $this->nullable ?? false;
    }

    public function default(): mixed
    {
        return $this->default;
    }

    public function unique(): bool
    {
        return $this->unique ?? false;
    }

    public function fillable(): bool
    {
        return $this->fillable ?? false;
    }

    public function isFillable(): self
    {
        $this->fillable = true;

        return $this;
    }

    public function hidden(): bool
    {
        return $this->hidden ?? false;
    }

    public function isHidden(): self
    {
        $this->hidden = true;

        return $this;
    }

    public function appended(): bool
    {
        return $this->appended ?? false;
    }

    public function isAppended(): self
    {
        $this->appended = true;

        return $this;
    }

    public function cast(): ?string
    {
        return $this->cast;
    }

    public function setCast(string $cast): self
    {
        $this->cast = $cast;

        return $this;
    }

    public function phpType(): ?string
    {
        return $this->phpType;
    }

    public function setPhpType(string $phpType): self
    {
        $this->phpType = $phpType;

        return $this;
    }

    public function typescriptType(): ?string
    {
        return $this->typescriptType;
    }

    public function setTypescriptType(string $typescriptType): self
    {
        $this->typescriptType = $typescriptType;

        return $this;
    }
}
