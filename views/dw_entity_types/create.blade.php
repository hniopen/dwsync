@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Dw Entity Type
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'dwsync.dwEntityTypes.store']) !!}

                        @include('dwsync::dw_entity_types.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
