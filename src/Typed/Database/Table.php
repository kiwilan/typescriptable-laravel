<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\Typed\Database\Types\MysqlColumn;
use Kiwilan\Typescriptable\Typed\Database\Types\PostgreColumn;
use Kiwilan\Typescriptable\Typed\Database\Types\SqliteColumn;
use Kiwilan\Typescriptable\Typed\Database\Types\SqlServerColumn;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModelAttribute;

class Table
{
    /** @var SchemaModelAttribute[] */
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
     * @return SchemaModelAttribute[]
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    public function addAttribute(SchemaModelAttribute $attribute): void
    {
        $this->attributes[$attribute->name()] = $attribute;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function driver(): string
    {
        return $this->driver;
    }

    public function select(): string
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
            $attribute = $driver::make($data);
            $attributes[$attribute->name()] = $attribute;
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
