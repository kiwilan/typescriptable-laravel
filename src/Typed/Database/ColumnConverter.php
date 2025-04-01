<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Kiwilan\Typescriptable\Typed\Schema\SchemaAttribute;

/**
 * Converts database fields into `SchemaAttribute` objects.
 */
class ColumnConverter
{
    protected function __construct(
        protected DriverEnum $driver, // `mysql`, `pgsql`, `sqlite`, etc.
        protected ?array $data = null, // Field form database
    ) {}

    /**
     * Creates a new instance of `FieldConverter`.
     *
     * @param  DriverEnum  $driver  The database driver.
     */
    public static function make(DriverEnum $driver): self
    {
        $self = new self(
            driver: $driver,
        );

        return $self;
    }

    /**
     * Parses database field into a `SchemaAttribute`.
     */
    public function parse(array|object $data): ?SchemaAttribute
    {
        $this->data = $this->handleData($data);

        $attribute = match ($this->driver) {
            DriverEnum::mysql => $this->mysql(),
            DriverEnum::mariadb => $this->mysql(),
            DriverEnum::pgsql => $this->postgre(),
            DriverEnum::sqlite => $this->sqlite(),
            DriverEnum::sqlsrv => $this->sqlServer(),
            DriverEnum::mongodb => null,
        };
        $attribute->handleTypes();

        return $attribute;
    }

    /**
     * Handle MySQL and MariaDB.
     */
    protected function mysql(): SchemaAttribute
    {
        $nullable = $this->data['Null'] ?? 'YES';
        $unique = $this->data['Key'] ?? null;
        $extra = $this->data['Extra'] ?? null;

        return new SchemaAttribute(
            name: $this->data['Field'] ?? null,
            databaseType: $this->data['Type'] ?? null,
            increments: $extra === 'auto_increment',
            nullable: $nullable === 'YES',
            default: $this->data['Default'] ?? null,
            unique: $unique === 'UNI',
            databaseFields: $this->data,
        );
    }

    /**
     * Handle SQL Server.
     */
    protected function sqlServer(): SchemaAttribute
    {
        $nullable = $this->data['IS_NULLABLE'] ?? null;

        return new SchemaAttribute(
            name: $this->data['COLUMN_NAME'] ?? null,
            databaseType: $this->data['DATA_TYPE'] ?? null,
            increments: false,
            nullable: $nullable === 'YES',
            default: $this->data['COLUMN_DEFAULT'] ?? null,
            databaseFields: $this->data,
        );
    }

    /**
     * Handle SQLite.
     */
    protected function sqlite(): SchemaAttribute
    {
        $increments = $this->data['pk'] ?? null;
        $nullable = $this->data['notnull'] ?? null;

        return new SchemaAttribute(
            name: $this->data['name'] ?? null,
            databaseType: $this->data['type'] ?? 'YES',
            increments: $increments === 1,
            nullable: $nullable === 0,
            default: $this->data['dflt_value'] ?? null,
            unique: false,
            databaseFields: $this->data,
        );
    }

    /**
     * Handle PostgreSQL.
     */
    protected function postgre(): SchemaAttribute
    {
        $nullable = $this->data['is_nullable'] ?? null;

        return new SchemaAttribute(
            name: $this->data['column_name'] ?? null,
            databaseType: $this->data['data_type'] ?? null,
            increments: false,
            nullable: $nullable === 'YES',
            default: $this->data['column_default'] ?? null,
            databaseFields: $this->data,
        );
    }

    /**
     * Converts the data to an array if it's an object.
     */
    private function handleData(array|object $data): array
    {
        if (is_object($data)) {
            return get_object_vars($data);
        }

        return $data;
    }
}
