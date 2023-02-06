<?php

namespace Kiwilan\Typescriptable\Services\Typescriptable;

use Kiwilan\Typescriptable\Services\Typescriptable\Utils\TypescriptableDbColumn;
use Kiwilan\Typescriptable\Services\Typescriptable\Utils\TypescriptableTypes;

/**
 * @property string[] $enum
 */
class TypescriptableProperty
{
    public function __construct(
        public string $table,
        public string $name,
        public ?TypescriptableDbColumn $dbColumn = null,
        public bool $isPrimary = false,
        public bool $isNullable = false,
        public ?string $phpType = null,
        public ?string $cast = null,
        public ?string $externalType = null,
        public array $enum = [],
        public bool $isExternal = false,
        public bool $isEnum = false,
        public bool $isRelation = false,
        public bool $isArray = false,
        public bool $isAppend = false,
        public bool $isCount = false,
        public bool $overrideTsType = false,
        public ?string $tsType = null,
        public ?string $tsString = null,
        public ?string $phpString = null,
    ) {
    }

    public static function make(
        string $table,
        TypescriptableDbColumn $dbColumn,
        bool $overrideTsType = false,
        bool $isRelation = false,
        bool $isAppend = false,
        bool $isArray = false,
        bool $isCount = false,
    ): self {
        $property = new self(
            table: $table,
            name: $dbColumn->Field,
            dbColumn: $dbColumn,
            isPrimary: $dbColumn->Key === 'PRI',
            isNullable: $dbColumn->Null === 'YES',
            overrideTsType: $overrideTsType,
        );

        if ($property->overrideTsType) {
            $property->phpType = $property->dbColumn->Type;
        } else {
            $property->phpType = TypescriptableTypes::phpType($dbColumn->Type);
        }

        $property->isRelation = $isRelation;
        $property->isAppend = $isAppend;
        $property->isArray = $isArray;
        $property->isCount = $isCount;
        $property->setPhpString();

        return $property;
    }

    public function setAdvancedType(): self
    {
        $type = TypescriptableTypes::docTypeToTsType($this);

        if ($type && ! $this->overrideTsType) {
            $this->phpType = $type;
            $this->tsType = $type;
            $this->overrideTsType = true;
        }

        return $this;
    }

    public function setPhpString(): self
    {
        $isNullable = $this->isNullable ? '?' : '';

        if ($this->isRelation) {
            $relationPrefix = 'App\\Types\\';
            $type = "{$relationPrefix}{$this->phpType}";
            $comment = $this->isArray ? '    /** @var \\'.$type.'*/' : '';
            $type = $this->isArray ? 'array' : '\\'.$type;
            $this->phpString = "{$comment}".PHP_EOL."    public {$type} \${$this->name};";
        } else {
            $type = "{$isNullable}{$this->phpType}";

            if ($this->name === 'mediable') {
                $type = 'mixed';
            }
            $this->phpString = "    public {$type} \${$this->name};";
        }

        $this->phpString = $this->phpString.PHP_EOL;

        return $this;
    }

    public function setTsType(): self
    {
        if (str_contains($this->phpType, '?')) {
            $this->phpType = str_replace('?', '', $this->phpType);
        }

        $this->tsType = TypescriptableTypes::phpToTs($this->phpType);

        if ($this->overrideTsType) {
            $this->tsType = $this->phpType;
        }
        $isNullable = $this->isNullable ? '?' : '';

        $this->tsString = "    {$this->name}{$isNullable}: {$this->tsType};";

        return $this;
    }

    /**
     * @param  string[]  $dates
     */
    public function convertDateType(array $dates): self
    {
        if (in_array($this->name, $dates)) {
            $this->phpType = 'DateTime';
        }

        return $this;
    }

    /**
     * @param  string[]  $casts
     */
    public function convertCastType(string $field, array $casts): self
    {
        if (! isset($casts[$field])) {
            return $this;
        }

        $this->cast = $casts[$field];
        $castType = TypescriptableTypes::castToPhpType($this->cast);

        $this->phpType = $castType;

        if (str_contains($this->cast, '\\')) {
            $this->isExternal = true;
            $this->externalType = $castType;
            $this->setEnum();
        }

        return $this;
    }

    private function setEnum(): self
    {
        $reflector = new \ReflectionClass($this->cast);
        $this->isEnum = TypescriptableTypes::isEnum($reflector);

        if ($this->isEnum) {
            $this->enum = TypescriptableTypes::setEnum($reflector);
            $this->overrideTsType = true;
            $this->phpType = TypescriptableTypes::phpEnumToTsType($this->enum);
        }

        return $this;
    }
}
