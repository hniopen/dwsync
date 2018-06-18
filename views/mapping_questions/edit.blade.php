@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Mapping Question
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($mappingQuestion, ['route' => ['dwsync.mappingQuestions.update', $mappingQuestion->id], 'method' => 'patch']) !!}

                        @include('dwsync::mapping_questions.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection