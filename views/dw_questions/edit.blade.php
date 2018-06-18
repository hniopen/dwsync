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
                   {!! Form::model($dwQuestion, ['route' => ['dwsync.dwQuestions.update', $dwQuestion->id], 'method' => 'patch']) !!}

                        @include('dwsync::dw_questions.fields')


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