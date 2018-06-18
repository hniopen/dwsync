<table class="table table-responsive" id="dwProjects-table">
    <thead>
        <tr>
            <th>Id</th>
        <th>Questcode</th>
        <th>longQuestCode</th>
        <th>Last submission</th>
        <th>Comment</th>
        <th>Isdisplayed</th>
        <th>AutoSync</th>
        <th>Entitytype</th>
        <th>Formtype</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($dwProjects as $dwProject)
        <tr>
            <td>{!! $dwProject->id !!}</td>
            <td>{!! $dwProject->questCode !!}</td>
            <td>{!! $dwProject->longQuestCode !!}</td>
            <td>
                @if($dwProject->getLastSubmission())
                    {!! $dwProject->getLastSubmission()->dwSubmittedAt !!}
                @endif
            </td>
            <td>{!! $dwProject->comment !!}</td>
            <td>{!! $dwProject->isDisplayed !!}</td>
            <td>
                <div>
                    <label><input type="checkbox" @if($dwProject->autoSync) checked @endif value="{!! $dwProject->id !!}"  class="chkBoxAutoSync" disabled><p id="chkBoxAutoSyncLabel"></p></label>
                </div>
            </td>
            <td>{!! $dwProject->DwEntityType->comment !!}</td>
            <td>{!! $dwProject->formType !!}</td>
            <td>
                {!! Form::open(['route' => ['dwsync.dwProjects.destroy', $dwProject->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dwsync.dwProjects.show', [$dwProject->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open" title="See details"></i></a>
                    <a href="{!! route('dwsync.dwQuestions.listQuestions', [$dwProject->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-th-list" title="List related questions"></i></a>
                    @can('dwsync_create_project')<a href="{!! route('dwsync.dwProjects.edit', [$dwProject->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit" title="Edit"></i></a>@endcan
                    @can('dwsync_create_project', 'dwsync_sync_data')<a href="{!! route('dwsync.dwProjects.extra', [$dwProject->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-fullscreen" title="Extra actions"></i></a>@endcan
                    @can('dwsync_create_project'){!! Form::button('<i class="glyphicon glyphicon-trash" title="Remove"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}@endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
    //TODO : listen chkBoxAutoSync events
</script>