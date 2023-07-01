<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Doctrine\DBAL\Types\Types;

class Column
{
    protected function __construct(
        public string $driver,
        public string $table,
        public string $name,
        public string $typeNative = 'text',
        public bool $isNullable = true,
        public bool $isPrimary = false,
        public string $typeFormat = 'string',
        public string $typePhp = 'string',
    ) {
    }

    public static function convert(
        string $driver,
        string $table,
        string $name,
        string $type = 'text',
        bool $isNullable = true,
        bool $isPrimary = false,
    ): self {
        $self = new self(
            $driver,
            $table,
            $name,
            $type,
            $isNullable,
            $isPrimary,
        );

        $self->typeFormat = $self->setTypeFormat();

        /** @var string|null */
        $phpType = match ($driver) {
            'mysql' => MysqlColumn::typeToPhp($self->typeFormat),
            'pgsql' => PostgreColumn::typeToPhp($self->typeFormat),
            'sqlite' => SqliteColumn::typeToPhp($self->typeFormat),
            'sqlsrv' => SqlServerColumn::typeToPhp($self->typeFormat),
            default => null,
        };

        if ($phpType === null) {
            throw new \Exception("Database driver not supported: {$driver}");
        }

        $self->typePhp = $phpType;

        if ($self->typePhp === Types::INTEGER) {
            $self->typePhp = 'int';
        }

        return $self;
    }

    public static function toArray(array|object $data): array
    {
        return is_object($data) ? get_object_vars($data) : $data;
    }

    private function setTypeFormat(): string
    {
        return explode(' ', preg_replace('/\s*\([^)]*\)/', '', $this->typeNative))[0] ?? $this->typeNative;
    }
}
