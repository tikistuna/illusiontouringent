<!DOCTYPE html>
<html lang="es-MX">
    <head>
        <meta charset="UTF-8">
        <title>Suscripci&oacute;n Recibida</title>
    </head>
    <body>
        <p>
            Estimado(a) {{$name ? $name : 'suscriptor'}},
        </p>
        <p>
            Hemos recibido exit&oacute;samente su suscripci&oacute;n para la ciudad de {{$city}}. Su e-mail ya ha sido verificado previamente,
            por lo tanto, ninguna acci&oacute;n es requerida de usted.
        </p>
    </body>
</html>