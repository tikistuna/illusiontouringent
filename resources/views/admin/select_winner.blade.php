@extends('admin.layouts.admin')

@section('content')
    @if(!isset($phone))
        <div class="card col-sm-9 mx-auto py-3 mb-5">
            <div class="card-body">
                <h1>Select a Winner</h1>
                <hr>
                <div class="row mt-3">
                    <div class="col-sm-9 mx-auto">
                        {!! Form::open(['method' => 'POST', 'action' => 'SuscriberController@select_winner']) !!}
                        <div class="form-group">
                            {!! Form::label('city', 'City:', ['class' => 'font-weight-bold']) !!}
                            {!! Form::select('city', array(''=>'Select a City')  + $cities , null,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('date', 'Date:', ['class' => 'font-weight-bold']) !!}
                            {!! Form::date('date', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <a href="{{route('events.index')}}" class="btn btn-secondary">Cancel</a>
                            {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container pt-5">
            <div class="row">
                <div class="col-12 col-lg-11">
                    <h1 class="text-white pt-4">
                        @if(empty($phone))
                            No encontramos ganador
                        @else
                            Ganador(a) es: {{$phone->name}}
                        @endif
                    </h1>
                    @if(!empty($phone))
                        <h2 class="h1 text-white">
                            Tel&eacute;fono: {{$phone->phoneForHumans()}}
                        </h2>
                    @endif
                </div>
            </div>
        </div>

    @endif
@stop
