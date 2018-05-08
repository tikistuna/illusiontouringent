<?php
    setlocale(LC_TIME, 'es_ES.UTF-8');
?>
@foreach($events as $event)

    {{ $event->getDateFormatted() }}
    <br/>

@endforeach
