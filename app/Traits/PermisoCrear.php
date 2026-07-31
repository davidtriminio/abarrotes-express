<?php
/*Código utilizado para controlar la creación de datos en los recursos*/
namespace App\Traits;
trait PermisoCrear
{
    public static function canAccess(array $parameters = []): bool
    {
        $slug = self::getResource()::getSlug();
        return auth()->user()->hasPermissionTo('crear:' . $slug);
    }
}
