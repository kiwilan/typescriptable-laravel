<?php

namespace Kiwilan\Typescriptable\Typed\Database;

use Illuminate\Support\Facades\Schema;

class DatabaseScan
{
    /**
     * @param  string[]  $tables
     * @param  array<string,Table>  $items
     */
    protected function __construct(
        protected string $driver,
        protected array $tables = [],
        protected array $items = [],
    ) {
    }

    public static function make(): self
    {
        $type = Schema::getConnection()->getDriverName();
        $property = match ($type) {
            'mysql' => MysqlColumn::TABLE_NAME,
            'pgsql' => PostgreColumn::TABLE_NAME,
            'sqlite' => SqliteColumn::TABLE_NAME,
            'sqlsrv' => SqlServerColumn::TABLE_NAME,
            default => throw new \Exception('Unsupported database type: '.$type),
        };

        $schemaTables = Schema::getAllTables();
        $tables = array_map(function (object $table) use ($property) {
            if (property_exists($table, $property)) {
                return $table->{$property};
            }
        }, $schemaTables);

        $items = [];
        foreach ($tables as $table) {
            $items[$table] = Table::make($table);
        }

        return new self(
            driver: $type,
            tables: $tables,
            items: $items,
        );
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @return string[]
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * @return array<string,Table>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
