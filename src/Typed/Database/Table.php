<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\Typed\Database\Types\IColumn;
use Kiwilan\Typescriptable\Typed\Database\Types\MysqlColumn;
use Kiwilan\Typescriptable\Typed\Database\Types\PostgreColumn;
use Kiwilan\Typescriptable\Typed\Database\Types\SqliteColumn;
use Kiwilan\Typescriptable\Typed\Database\Types\SqlServerColumn;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModelAttribute;

/**
 * Database table.
 */
class Table
{
    /** @var SchemaModelAttribute[] */
    protected array $attributes = [];

    protected function __construct(
        protected string $driver,
        protected string $name,
        protected ?string $select = null,
    ) {}

    /**
     * Create new table from table name.
     *
     * @param  string  $table  Table name from database.
     */
    public static function make(string $table): self
    {
        $self = new self(
            driver: Schema::getConnection()->getDriverName(),
            name: $table
        );

        $self->select = $self->setSelect();
        $self->attributes = $self->setAttributes();

        return $self;
    }

    /**
     * Get all `SchemaModelAttribute` of the table, so all columns.
     *
     * @return SchemaModelAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Add an attribute to the table (for accessors).
     */
    public function addAttribute(SchemaModelAttribute $attribute): void
    {
        $this->attributes[$attribute->getName()] = $attribute;
    }

    /**
     * Get the name of the table.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get driver used by the table, like `mysql` or `sqlite`.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Get the select query used to get the attributes.
     */
    public function getSelect(): string
    {
        return $this->select;
    }

    /**
     * @return SchemaModelAttribute[]
     */
    private function setAttributes(): array
    {
        /** @var SchemaModelAttribute[] */
        $attributes = [];

        $driver = match ($this->driver) {
            'mysql' => MysqlColumn::class,
            'mariadb' => MysqlColumn::class,
            'pgsql' => PostgreColumn::class,
            'sqlite' => SqliteColumn::class,
            'sqlsrv' => SqlServerColumn::class,
            'mongodb' => 'mongodb',
            default => null,
        };

        if ($driver === null) {
            throw new \Exception("Database driver not supported: {$this->driver}");
        }

        $schemaTables = [];
        if (intval(app()->version()) >= 11) {
            $schemaTables = Schema::getTableListing();
        } else {
            $schemaTables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames(); // @phpstan-ignore-line
        }

        if (! in_array($this->name, $schemaTables)) {
            return [];
        }

        $select = $this->driver === 'mongodb' ? [] : DB::select($this->select);
        foreach ($select as $data) {
            if ($this->driver === 'mongodb') {
                continue;
            }
            /** @var IColumn $driver */
            /** @var SchemaModelAttribute $attribute */
            $attribute = $driver::make($data);
            $attributes[$attribute->getName()] = $attribute;
        }

        return $attributes;
    }

    private function setSelect(): ?string
    {
        return match ($this->driver) {
            'mysql' => "SHOW COLUMNS FROM {$this->name}",
            'mariadb' => "SHOW COLUMNS FROM {$this->name}",
            'pgsql' => "SELECT column_name, data_type, is_nullable, column_default FROM information_schema.columns WHERE table_name = '{$this->name}'",
            'sqlite' => "PRAGMA table_info({$this->name})",
            'sqlsrv' => "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->name}'",
            'mongodb' => null,
            default => "SHOW COLUMNS FROM {$this->name}",
        };
    }
}
