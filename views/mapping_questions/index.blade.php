@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Mapping Questions</h1>
        @if(View::exists('dwsync::mapping_questions.create'))
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('dwsync.mappingQuestions.create') !!}">Add New</a>
        </h1>
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('dwsync::mapping_questions.table')
            </div>
        </div>
    </div>
@endsection

