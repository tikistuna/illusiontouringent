<html>
<body>
<table>
    <thead style="background-color: #ec1313; color:#f8f9fa">
    <tr>
        <th>#</th>
        <th></th>
        <th>Artista</th>
        <th>Ciudad</th>
        <th>D&iacute;a</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Precios</th>
        <th>Teatro</th>
        <th>Direcci&oacute;n</th>
        <th>Taquilla</th>
        <th>Tel&eacute;fono</th>
        <th>Boletera</th>
        <th>Tel&eacute;fono</th>
    </tr>
    </thead>
    <tbody>
    @foreach($events as $event)
        <tr>
            <td>{{$loop->index}}</td>
            @if($event->illusion)
                <td style="background-color: #0066ff; color:#f8f9fa">Illusion</td>
            @else
                <td style="background-color: #ec1313; color: #fff">L.J.</td>
            @endif
            <td style="background-color: #ffde00">{{$event->name}}</td>
            <td style="background-color: #ec1313; color: #fff;">{{$event->city->name}}</td>
            <td style="background-color: #d9d9d9">{{$event->firstLetterOfDay()}}</td>
            <td style="background-color:#2eb82e; color:#f8f9fa">{{$event->date->formatLocalized('%b-%d')}}</td>
            <td style="background-color: #d9d9d9">{{$event->date->formatLocalized('%I:%M')}}</td>
            <td style="background-color: #b3d9ff">{{$event->pricesAsString}}</td>
            <td style="background-color: #d9d9d9">{{$event->venue->name}}</td>
            <td style="background-color: #bbff99">{{$event->venue->address}}</td>
            <td style="background-color: #bbff99">{{$event->venue->phone}}</td>
            <td style="background-color: #bbff99">{{$event->venue->hours}}</td>
            @if($event->ticket_sellers->count() > 0)
                <td style="background-color: #d9d9d9">{{implode(', ', $event->ticket_sellers->pluck('name')->toArray())}}</td>
                <td style="background-color: #ffd699">
                    @foreach($event->ticket_sellers as $ticket_seller)
                        {{$ticket_seller->phone}},
                    @endforeach
                </td>
            @else
                <td style="background-color: #d9d9d9"></td>
                <td style="background-color: #ffd699"></td>
            @endif
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>#</th>
        <th></th>
        <th>Artista</th>
        <th>Ciudad</th>
        <th>D&iacute;a</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Precios</th>
        <th>Teatro</th>
        <th>Direcci&oacute;n</th>
        <th>Taquilla</th>
        <th>Tel&eacute;fono</th>
        <th>Boletera</th>
        <th>Tel&eacute;fono</th>
    </tr>
    </tfoot>
</table>
</body>
</html>