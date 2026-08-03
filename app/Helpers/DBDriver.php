<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

/**
 * Helper to detect the current database driver and run driver-specific SQL or callbacks.
 *
 * Usage examples:
 *
 * // simple check
 * if (DBDriver::isMysql()) { ... }
 *
 * // execute different SQL depending on driver
 * DBDriver::executeByDriver([
 *     'mysql' => "ALTER TABLE users ADD COLUMN ...",
 *     'pgsql' => "ALTER TABLE users ADD COLUMN ...",
 *     'default' => function($connection) { // fallback
 *         return $connection->statement('...');
 *     }
 * ]);
 *
 * // convenience for mysql vs pgsql
 * DBDriver::executeForMysqlAndPgsql($mysqlSql, $pgsqlSql);
 */
class DBDriver
{
    /**
     * Return the current connection driver name in lowercase (e.g. "mysql", "pgsql").
     */
    public static function driver(): string
    {
        try {
            $connection = DB::connection();
            $driverName = $connection->getDriverName();
            return strtolower($driverName ?: (string) config('database.default', 'mysql'));
        } catch (\Throwable $e) {
            return strtolower((string) config('database.default', 'mysql'));
        }
    }

    public static function isMysql(): bool
    {
        return self::driver() === 'mysql';
    }

    public static function isPgsql(): bool
    {
        $d = self::driver();
        return in_array($d, ['pgsql', 'postgres', 'postgresql'], true);
    }

    /**
     * Execute a driver-specific statement or callback.
     *
     * $map can contain keys like 'mysql', 'pgsql', other drivers or 'default'.
     * Each value can be a SQL string or a callable that receives the connection instance.
     *
     * @param array $map
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function executeByDriver(array $map)
    {
        $driver = self::driver();

        if (isset($map[$driver])) {
            $action = $map[$driver];
        } elseif (isset($map['default'])) {
            $action = $map['default'];
        } else {
            throw new \InvalidArgumentException("No action provided for driver '{$driver}' and no 'default' entry.");
        }

        if (is_callable($action)) {
            return $action(DB::connection());
        }

        // treat as raw SQL
        return DB::statement($action);
    }

    /**
     * Convenience for the common case: one SQL for MySQL and another for PostgreSQL.
     *
     * @param string $mysqlSql
     * @param string $pgsqlSql
     * @return mixed
     */
    public static function executeForMysqlAndPgsql(string $mysqlSql, string $pgsqlSql)
    {
        return self::executeByDriver([
            'mysql' => $mysqlSql,
            'pgsql' => $pgsqlSql,
            'postgres' => $pgsqlSql,
            'postgresql' => $pgsqlSql,
        ]);
    }
}

