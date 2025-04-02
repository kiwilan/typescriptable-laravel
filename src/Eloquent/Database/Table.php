<?php

namespace Kiwilan\Typescriptable\Eloquent\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaAttribute;

/**
 * Represents a database table with its columns.
 */
class Table
{
    /** @var SchemaAttribute[] */
    protected array $attributes = [];

    protected function __construct(
        protected DriverEnum $driver, // `mysql`, `pgsql`, `sqlite`, etc.
        protected string $name, // Table name, e.g., `users`
        protected ?string $select = null, // SQL query to select columns
        protected array $columns = [], // Columns of the table
    ) {}

    /**
     * Creates a new instance of `Table`.
     *
     * @param  string  $table  The name of the table.
     * @param  DriverEnum|null  $driverOverride  The database driver to override the default one.
     */
    public static function make(string $table, ?DriverEnum $driverOverride = null): self
    {
        $self = new self(
            driver: $driverOverride ?? DriverEnum::tryFrom(Schema::getConnection()->getDriverName()),
            name: $table,
        );

        if ($self->driver === DriverEnum::mongodb) {
            throw new \Exception('MongoDB driver use manual parser.');
        }

        $self->select = $self->writeSelect();
        $self->columns = $self->parseColumns();
        $self->attributes = $self->parseAttributes();

        return $self;
    }

    /**
     * Get attributes (converted columns) of the table.
     *
     * @return SchemaAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get a specific attribute by its name.
     */
    public function getAttribute(string $name): ?SchemaAttribute
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Add a attribute.
     */
    public function addAttribute(SchemaAttribute $attribute): void
    {
        $this->attributes[$attribute->getName()] = $attribute;
    }

    /**
     * Get the columns of the table.
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Get the name of the table.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the database driver name.
     */
    public function getDriver(): DriverEnum
    {
        return $this->driver;
    }

    /**
     * Get the SQL query to select the columns of the table.
     */
    public function getSelect(): string
    {
        return $this->select;
    }

    /**
     * Converts the columns of the table into `SchemaAttribute` objects.
     *
     * @return SchemaAttribute[]
     */
    private function parseAttributes(): array
    {
        /** @var SchemaAttribute[] */
        $attributes = [];

        $converter = ColumnConverter::make($this->driver);

        // Convert each column into a `SchemaAttribute`
        foreach ($this->columns as $column) {
            $attribute = $converter->parse($column);
            $attributes[$attribute->getName()] = $attribute;
        }

        return $attributes;
    }

    /**
     * If the driver is MongoDB, we don't need to select columns. Otherwise, we execute the SQL query to get the columns.
     */
    private function parseColumns(): array
    {
        return $this->driver === DriverEnum::mongodb ? [] : DB::select($this->select);
    }

    // /**
    //  * Get the list of tables in the database.
    //  *
    //  * @return string[]
    //  */
    // private function getTableList(): array
    // {
    //     // Get the list of tables in the database
    //     $tables = [];
    //     if (intval(app()->version()) >= 11) {
    //         $tables = Schema::getTableListing(); // For Laravel 11 and above
    //     } else {
    //         // For Laravel 10 and below
    //         $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
    //     }

    //     // If the table is not in the schema, return an empty array
    //     if (! in_array($this->name, $tables)) {
    //         return [];
    //     }

    //     return $tables;
    // }

    /**
     * Write the SQL query to select the columns of the table.
     */
    private function writeSelect(): ?string
    {
        return match ($this->driver) {
            DriverEnum::mysql => "SHOW COLUMNS FROM {$this->name}",
            DriverEnum::mariadb => "SHOW COLUMNS FROM {$this->name}",
            DriverEnum::pgsql => "SELECT column_name, data_type, is_nullable, column_default FROM information_schema.columns WHERE table_name = '{$this->name}'",
            DriverEnum::sqlite => "PRAGMA table_info({$this->name})",
            DriverEnum::sqlsrv => "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->name}'",
            DriverEnum::mongodb => null,
        };
    }
}
