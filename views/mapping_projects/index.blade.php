@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Mapping Projects</h1>
        <h1 class="pull-right">
            <img src="{{ asset('gif-load.gif') }}"
                 class="gif-loading"
                 style="margin-bottom: 5px;margin-top: -7px;margin-right: 4px;display: none">
            <a id="pushAllBulk" class="btn btn-success" style="margin-top: -10px;margin-bottom: 5px;margin-right: 5px;"
               href="#/" onclick="pushAllMappingForBulk();">Push IDNR to DW</a>
            @if(View::exists('dwsync::mapping_projects.create'))
            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('dwsync.mappingProjects.create') !!}">Add New</a>
            @endif
        </h1>

    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('dwsync::mapping_projects.table')
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var url = '{{route('dwsync.dwProjects.pushIdnr')}}';

        function pushAllMappingForBulk(){
            if(confirm('Are you sure to run sync, this may take long time?')){
                ajaxpushAllIdnr();
            }
        }

        function ajaxpushAllIdnr() {
            $(".gif-loading").show();
            $.ajax({
                type: 'get',
                url: url,
                dataType: 'json',
                data: {},
                success: function (data, textStatus) {
                    var msg = '';
                    if(data.status == 'success'){
                        msg += data.nbTotal +' supposed to be pushed\n';
                        msg += data.nbSuccess +' pushed successfully\n';
                        msg += data.nbFail +' Failed\n';
                    }
                    else{
                        msg += data.message;
                    }
                    alert(msg)
                    $(".gif-loading").hide();
                },
                error: function (xhr, textStatus, errorThrown) {
                    alert(xhr.responseText)
                    $(".gif-loading").hide();
                }
            });
        }
    </script>
@endsection

