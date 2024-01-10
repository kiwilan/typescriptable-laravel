<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\TypescriptableConfig;

class Table
{
    /** @var Column[] */
    public array $columns = [];

    protected function __construct(
        public string $driver,
        public string $name,
        public ?string $select = null,
    ) {
    }

    public static function getName(Model|string $model): string
    {
        if ($model instanceof Model) {
            $name = $model->getTable();
        } else {
            $name = $model;
        }
        $prefix = TypescriptableConfig::databasePrefix();

        if ($prefix) {
            $name = "{$prefix}{$name}";
        }

        return $name;
    }

    public static function make(string $table): self
    {
        $self = new self(
            driver: Schema::getConnection()->getDriverName(),
            name: $table
        );

        $self->select = $self->setSelect();
        $self->columns = $self->setColumns();

        return $self;
    }

    /**
     * @return Column[]
     */
    private function setColumns(): array
    {
        /** @var Column[] */
        $columns = [];

        /** @var Column|null */
        $converter = match ($this->driver) {
            'mysql' => fn ($column) => MysqlColumn::make($column, $this->name, $this->driver),
            'pgsql' => fn ($column) => PostgreColumn::make($column, $this->name, $this->driver),
            'sqlite' => fn ($column) => SqliteColumn::make($column, $this->name, $this->driver),
            'sqlsrv' => fn ($column) => SqlServerColumn::make($column, $this->name, $this->driver),
            default => null,
        };

        if ($converter === null) {
            throw new \Exception("Database driver not supported: {$this->driver}");
        }

        if (! Schema::hasTable($this->name)) {
            return [];
        }

        foreach (DB::select($this->select) as $column) {
            $columns[] = $converter($column);
        }

        return $columns;
    }

    private function setSelect(): string
    {
        return match ($this->driver) {
            'mysql' => "SHOW COLUMNS FROM {$this->name}",
            'pgsql' => "SELECT column_name, data_type FROM information_schema.columns WHERE table_name = '{$this->name}'",
            'sqlite' => "PRAGMA table_info({$this->name})",
            'sqlsrv' => "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->name}'",
            default => "SHOW COLUMNS FROM {$this->name}",
        };
    }
}
