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
    ) {}

    public static function make(): self
    {
        $type = Schema::getConnection()->getDriverName();

        $schemaTables = [];
        if (intval(app()->version()) >= 11) {
            $schemaTables = Schema::getTableListing();
        } else {
            $schemaTables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames(); // @phpstan-ignore-line
        }

        $items = [];
        foreach ($schemaTables as $table) {
            $items[$table] = Table::make($table);
        }

        return new self(
            driver: $type,
            tables: $schemaTables,
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
