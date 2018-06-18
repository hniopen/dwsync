@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Dw Questions</h1>
        <h1 class="pull-right">
            @can('dwsync_create_project')<a class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('dwsync.dwQuestions.create') !!}" title="">Add new</a>@endcan
            {{--<label>Add new : </label>--}}
           {{--<a class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('dwsync.dwQuestions.create') !!}" title="One by one">Single question</a>--}}
           {{--<a class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('dwsync.dwQuestions.createFromSubmissions') !!}" title="">From existing submissions</a>--}}
           {{--<a class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('dwsync.dwQuestions.createFromXlsform') !!}" title="">From xlsform</a>--}}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('dwsync::dw_questions.table')
            </div>
        </div>
    </div>
@endsection

