<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña</title>
</head>
<body>
<h1>¡Restablece tu contraseña!</h1>

<p>Hola {{ $user->name }},</p>

<p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Si no fuiste tú quien realizó esta solicitud, simplemente ignora este correo.</p>

<p>Para restablecer tu contraseña, haz clic en el siguiente enlace:</p>

<a href="{{ $resetLink }}" style="color: #fff; background-color: #007bff; padding: 10px 15px; text-decoration: none;">
    Restablecer Contraseña
</a>

<p>Si tienes algún problema, por favor contáctanos a support@tudominio.com</p>

<p>Saludos,</p>
<p>El equipo de {{ config('app.name') }}</p>
</body>
</html>
