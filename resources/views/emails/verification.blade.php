<!DOCTYPE html>
<html lang="es-MX">
    <head>
        <meta charset="UTF-8">
        <title>Verificaci&oacute;n de e-mail</title>
    </head>
    <body>
        <p>
            Estimado(a) {{$name ? $name : 'suscriptor'}},
        </p>
        <p>
            Hemos recibido exit&oacute;samente su suscripci&oacute;n para la ciudad de {{$city}}
            <br/>
            Por favor verifique su e-mail haciendo click en el siguiente enlace:
            <a href="https://ljconciertos.com/suscribers/verification/email/{{$email}}/{{$validation_code}}">Verificar</a>
        </p>
    </body>
</html>