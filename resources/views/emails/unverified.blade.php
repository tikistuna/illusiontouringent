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
            Para seguir participando para ganar boletos y/o meet and greet con sus artistas favoritos, por favor verifique su e-mail haciendo click en el siguiente enlace:
            <a href="https://illusiontouringent.com/suscribers/verification/email/{{$email}}/{{$validation_code}}">Verificar</a>
        </p>
    </body>
</html>