@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Dw Question
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'dwsync.dwQuestions.storeFromSubmissions']) !!}

                    <!-- Projectid Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('projectId', 'Projectid:') !!}
                            {!! Form::select('projectId', $dwProjectList, null, ['class' => 'form-control']) !!}
                        </div>

                        <!-- Xlsform Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('xlsformFileId', 'Xlsform File:') !!}
                            {!! Form::file('xlsform', $attributes = array()) !!}
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('dwsync.dwQuestions.index') !!}" class="btn btn-default">Cancel</a>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
