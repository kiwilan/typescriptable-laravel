<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\Typed\Schema\SchemaAttribute;

class Table
{
    /** @var SchemaAttribute[] */
    protected array $attributes = [];

    protected function __construct(
        protected string $driver,
        protected string $name,
        protected ?string $select = null,
    ) {}

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
     * @return SchemaAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function addAttribute(SchemaAttribute $attribute): void
    {
        $this->attributes[$attribute->getName()] = $attribute;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getSelect(): string
    {
        return $this->select;
    }

    /**
     * @return SchemaAttribute[]
     */
    private function setAttributes(): array
    {
        /** @var SchemaAttribute[] */
        $attributes = [];

        $driver = match ($this->driver) {
            'mysql' => \Kiwilan\Typescriptable\Typed\Database\Driver\MysqlColumn::class,
            'mariadb' => \Kiwilan\Typescriptable\Typed\Database\Driver\MysqlColumn::class,
            'pgsql' => \Kiwilan\Typescriptable\Typed\Database\Driver\PostgreColumn::class,
            'sqlite' => \Kiwilan\Typescriptable\Typed\Database\Driver\SqliteColumn::class,
            'sqlsrv' => \Kiwilan\Typescriptable\Typed\Database\Driver\SqlServerColumn::class,
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
            /** @var SchemaAttribute */
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
