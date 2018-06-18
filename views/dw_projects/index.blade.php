@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Dw Projects</h1>
        <h1 class="pull-right">
            @can('dwsync_sync_data')
                <a id="syncAllBulk" class="btn btn-success" style="margin-top: -10px;margin-bottom: 5px"
                   href="#/" onclick="syncAllProjectForBulk();">Sync all from DW</a>
            @endcan
            @can('dwsync_create_project')
            <a id="addNew" class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px"
               href="{!! route('dwsync.dwProjects.create') !!}">Add New</a>
                @endcan
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div id="progressBar" style="display: none;">
            {{--<h1 id="progreBarHead">Syncing ...</h1>--}}
            <div class="progress" width="20">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45"
                     aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <h4 class="modal-title" id="myModalLabel"><p id="count"></p>%</h4>
                </div>
            </div>
            <div class="note note-info">
                <ul class="" id="syncNote">
                    {{--<li id="startNote">Start</li>--}}
                    <li id="endNote">End</li>
                </ul>
            </div>
        </div>
        <div class="box box-primary" id="projectTable">
            <div class="box-body">
                @include('dwsync::dw_projects.table')
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var allProjectUrl = [];//supposed to be already sorted by entityType ASC : DS >I >Q
        var statusOk = 0;
        var percentage = 0;
        var currentdate;
                @foreach($dwProjects as $dwProject)
                @if($dwProject->autoSync > 0)
        var url = '{{route('dwsync.dwProjects.sync', '__id__')}}';
        var id = '{{$dwProject->id }}';
        allProjectUrl.push(url.replace("__id__", id));
        @endif
        @endforeach

        function syncAllProjectForBulk() {
            if (confirm('Are you sure to run sync, this may take long time?')) {
                statuStartSyncBulk();
                ajaxSyncSingleProjectForBulk(0);
            }
        }

        function statuStartSyncBulk() {
            console.log("Start syncing");
            $(".progress-bar").addClass("active");
            $("#progressBar").show();
            $("#endNote").hide();
            percentage = 0;
            statusOk = 0;
            currentdate = new Date();
            notifOnProgressBar(0, "Start syncing, please don't try to force stopping");
            $("#syncAllBulk").addClass('disabled');
            $("#addNew").addClass('disabled');
            $("#projectTable").hide();
        }

        function statusEndSyncBulk() {
            $("#syncAllBulk").removeClass('disabled');
            $("#addNew").removeClass('disabled');
            $("#projectTable").show();
            var datetime = "[" + currentdate.getFullYear() + (currentdate.getMonth() + 1) + "-" + currentdate.getDate() + " "
                + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds() + "]";
            $("#endNote").html(datetime + " End");
            $("#endNote").show();
            $(".progress-bar").removeClass("active");
            console.log("End syncing");
        }

        function notifOnProgressBar(increment, message) {
            statusOk += increment;
            percentage = Math.round(statusOk / allProjectUrl.length * 100);
            if (percentage == 0) {
                $('.progress').css('width', 1 + "%");
            } else {
                $('.progress').css('width', percentage + "%");
            }
            $("#count").html(percentage + "%");
            var datetime = "[" + currentdate.getFullYear() + (currentdate.getMonth() + 1) + "-" + currentdate.getDate() + " "
                + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds() + "]";
            $("<li>" + datetime + " " + message + "</li>").insertBefore("#endNote");
            currentdate = new Date();//next
        }

        function ajaxSyncSingleProjectForBulk(id) {
            var url = allProjectUrl[id];
            console.log("URL : " + url);
            $.ajax({
                type: 'get',
                url: url,
                dataType: 'json',
                data: {},
                success: function (data, textStatus) {
                    console.log("Data " + JSON.stringify(data));
                    var resultJson = data['result'] ? JSON.stringify(data['result']) : "No result";
                    var resultSubmissions = data['submissions'] ? JSON.stringify(data['submissions']) : "No submissions";
                    var message = data['message']['text'];// + " <br> " + data['submissions']['status'] ;
                    notifOnProgressBar(1, message + '<span class="label label-success">Success !</span>');
                    if (id < allProjectUrl.length - 1) {
                        ajaxSyncSingleProjectForBulk(id + 1)
                    } else {
                        statusEndSyncBulk();
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
//                    var message = 'Error : ' + xhr.responseText;
                    var btnExpand = '<a data-toggle="collapse" data-target="#collapse' + id + '" aria-expanded="false" aria-controls="collapse' + id + '">' +
                        'See error</a>';
                    var message = url + ' <span class="label label-danger">Failed : </span>' + btnExpand;
                    if (xhr.responseText.length) {
                        message += '<div class="collapse" id="collapse' + id + '">' +
                            '<div class="card card-block">' + xhr.responseText + '</div>' +
                            '</div>';
                    }
                    notifOnProgressBar(0, message);
                    if (id < allProjectUrl.length - 1) {
                        ajaxSyncSingleProjectForBulk(id + 1)
                    } else {
                        statusEndSyncBulk();
                    }
                }
            });
        }
    </script>
@endsection

