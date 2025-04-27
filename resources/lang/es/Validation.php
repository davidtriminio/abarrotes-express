<?php

return [
    'required' => ' :attribute es obligatorio.',
    'email' => ':attribute debe ser una dirección de correo electrónico válida.',
    'exists' => ':attribute no existe en nuestros registros.',
    'string' => ':attribute debe ser una cadena de texto.',
    'max' => [
        'string' => ':attribute no debe ser mayor que :max caracteres.',
    ],
    'min' => [
        'string' => ':attribute debe tener al menos :min caracteres.',
    ],

    'attributes' => [
        'email' => 'correo electrónico',
        'recovery_key' => 'clave de recuperación',
    ],
];
