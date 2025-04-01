<?php

namespace Kiwilan\Typescriptable\Tests\Data\Utils;

class Driver
{
    public const SQLITE = 'sqlite';

    public const MYSQL = 'mysql';

    public const MARIADB = 'mariadb';

    public const PGSQL = 'pgsql';

    public const SQLSRV = 'sqlsrv';

    public const MONGODB = 'mongodb';

    public function __construct(
        public ?string $name = null,
        public ?string $host = null,
        public ?string $port = null,
        public ?string $url = null,
        public ?string $database = null,
        public ?string $user = null,
        public ?string $password = null,
        public ?string $prefix = null,
    ) {}

    public static function all(): array
    {
        return [
            self::SQLITE,
            self::MYSQL,
            self::MARIADB,
            self::PGSQL,
            self::SQLSRV,
            self::MONGODB,
        ];
    }
}
