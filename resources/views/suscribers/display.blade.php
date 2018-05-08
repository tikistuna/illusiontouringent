@extends('layouts.public')

@section('main')
    <div class="container">
        <div class="col-12 mx-auto">
            <div class="card bg-light card-form">
                <div class="card-body">
                    <h4 class="card-title">Mostrando mensajes</h4>

                        @if(session('message_phone'))

                        @endif
                </div>
            </div>
        </div>
    </div>
@stop