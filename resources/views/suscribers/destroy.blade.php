@extends('layouts.public')

@section('head')
    <link rel="stylesheet" href="/css/public-subscribers-delete.css">
@stop

@section('main')
    <div class="container py-5">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 mx-auto text-white pb-3">
            <div class="card bg-dark card-form">
                <div class="card-body">
                    <p>Ingrese la ciudad de la que le gustar&iacute;a dejar de recibir publicidad. Adicionalmente, ingrese
                        sus datos para el/los medio(s) por los que dejar&aacute; de recibir publicidad.
                    </p>
                    <div class="mt-4">
                        {!! Form::open(['method' => 'DELETE', 'action' => 'SuscriberController@destroy']) !!}
                        <div class="form-group">
                            {!! Form::label('phone', 'Tel&eacute;fono:') !!}
                            {!! Form::text('phone', null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('city', 'Ciudad:') !!}
                            {!! Form::select('city', array('' => 'Select a City') + $cities, null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit('Continuar', ['class'=>'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop