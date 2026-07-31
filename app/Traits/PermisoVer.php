<?php
/*Código utilizado para controlar la vista de datos en los recursos*/

namespace App\Traits;
trait PermisoVer
{
    public static function canAccess(array $parameters = []): bool
    {
        $slug = self::getResource()::getSlug();
        return auth()->user()->hasPermissionTo('ver:' . $slug);
    }
}
