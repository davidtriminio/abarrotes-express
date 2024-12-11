<?php
/*Código utilizado para controlar la edición de datos en los recursos*/
namespace App\Traits;
trait PermisoEditar
{
    public static function canAccess(array $parameters = []): bool
    {
        $slug = self::getResource()::getSlug();
        return auth()->user()->hasPermissionTo('editar:' . $slug);
    }
}
