<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "db.default=" . config('database.default') . PHP_EOL;
echo "db.driver=" . config('database.connections.' . config('database.default') . '.driver') . PHP_EOL;
echo "session.driver=" . config('session.driver') . PHP_EOL;
echo "cache.default=" . config('cache.default') . PHP_EOL;
echo "config.cached=" . (file_exists(__DIR__ . '/bootstrap/cache/config.php') ? 'yes' : 'no') . PHP_EOL;

$rawUser = DB::table('users')->where('id', 53)->first(['id', 'email', 'name']);
echo 'raw.user=' . json_encode($rawUser) . PHP_EOL;

$user = App\Models\User::query()->whereKey(53)->first(['id', 'email', 'name', 'password']);
echo 'model.user.attributes=' . json_encode($user?->getAttributes()) . PHP_EOL;
echo 'model.user.relations=' . json_encode($user?->getRelations()) . PHP_EOL;

if ($user) {
    echo 'model.user.array.length=' . strlen(json_encode($user->toArray())) . PHP_EOL;
    echo 'has.permission.ver.admin=';
    try {
        var_export($user->hasPermissionTo('ver:admin'));
        echo PHP_EOL;
    } catch (Throwable $e) {
        echo 'ERROR ' . get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
    }

    echo 'roles.count=';
    try {
        echo $user->roles()->count() . PHP_EOL;
    } catch (Throwable $e) {
        echo 'ERROR ' . get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
    }

    echo 'permissions.rows=';
    try {
        echo DB::table('permissions')->count() . PHP_EOL;
    } catch (Throwable $e) {
        echo 'ERROR ' . get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . 'table.counts=' . PHP_EOL;
foreach (['users', 'productos', 'categorias', 'marcas', 'sessions', 'cache', 'roles', 'permissions', 'model_has_roles'] as $table) {
    try {
        if (Schema::hasTable($table)) {
            echo $table . '=' . DB::table($table)->count() . PHP_EOL;
        } else {
            echo $table . '=missing' . PHP_EOL;
        }
    } catch (Throwable $e) {
        echo $table . '=ERROR ' . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . 'large.columns=' . PHP_EOL;
foreach ([
    ['productos', 'imagenes'],
    ['productos', 'imagen1'],
    ['productos', 'imagen2'],
    ['productos', 'imagen3'],
    ['productos', 'imagen4'],
    ['productos', 'imagen5'],
    ['sessions', 'payload'],
    ['cache', 'value'],
] as [$table, $column]) {
    try {
        if (Schema::hasColumn($table, $column)) {
            $max = DB::table($table)->max(DB::raw('CHAR_LENGTH(' . $column . ')'));
            echo $table . '.' . $column . '.max=' . (int) $max . PHP_EOL;
        }
    } catch (Throwable $e) {
        echo $table . '.' . $column . '=ERROR ' . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . 'home.query.sizes=' . PHP_EOL;
try {
    $productos = App\Models\Producto::orderBy('en_oferta', 'desc')->limit(4)->get();
    echo 'productos.bytes=' . strlen($productos->toJson()) . PHP_EOL;
    $categorias = App\Models\Categoria::inRandomOrder()->limit(4)->get();
    echo 'categorias.bytes=' . strlen($categorias->toJson()) . PHP_EOL;
    $marcas = App\Models\Marca::inRandomOrder()->limit(3)->get();
    echo 'marcas.bytes=' . strlen($marcas->toJson()) . PHP_EOL;
} catch (Throwable $e) {
    echo 'home.query.ERROR ' . get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
}
