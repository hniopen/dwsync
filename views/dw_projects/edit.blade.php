@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Dw Project
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($dwProject, ['route' => ['dwsync.dwProjects.update', $dwProject->id], 'method' => 'patch']) !!}

                        @include('dwsync::dw_projects.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection