<?php

// function driverEnums(): array
// {
//     $dotenv = Dotenv::createMutable(getcwd());
//     $data = $dotenv->load();
//     $types = $data['DATABASE_TYPES'] ?? 'mysql,mariadb,sqlite,pgsql,sqlsrv';
//     $types = explode(',', $types);

//     return $types;
// }

// function driverEnumsWithoutSqlsrv(): array
// {
//     $drivers = DriverEnums();
//     if (($key = array_search('sqlsrv', $drivers)) !== false) {
//         unset($drivers[$key]);
//     }

//     return $drivers;
// }

/**
 * Delete a file if it exists.
 */
function deleteFile(string $file_path): void
{
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

/**
 * Delete a directory and all its contents.
 *
 * @param  string  $directory  The path to the directory to delete.
 */
function deleteDirectory(string $directory): void
{
    $files = glob("{$directory}/*");
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        if ($file === '.gitignore') {
            continue;
        }
        if (is_file($file)) {
            unlink($file);
        } elseif (is_dir($file)) {
            deleteDirectory($file);
            rmdir($file);
        }
    }
}
