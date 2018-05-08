@extends('admin.layouts.admin')

@section('main')
    <div class="card col-sm-9 mx-auto">
        <div class="card-body">
            <h1>Parse a File</h1>
            <hr>
            <div class="row mt-3">
                <div class="col-sm-9 mx-auto">
                    {!! Form::open(['method' => 'POST', 'action' => 'AdminController@parse_file']) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Filename:') !!}
                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection