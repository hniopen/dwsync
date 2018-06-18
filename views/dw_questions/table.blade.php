<table class="table table-responsive" id="dwQuestions-table">
    <thead>
    <tr>
        <th>Id</th>
        <th>Projectid</th>
        <th>Xformquestionid</th>
        <th>Questionid</th>
        <th>Path</th>
        <th>Labeldefault</th>
        <th>Labelfr</th>
        <th>Labelus</th>
        <th>Datatype</th>
        <th>Dataformat</th>
        <th>Order</th>
        <th>Linkedidnr</th>
        <th>Periodtype</th>
        <th>Periodtypeformat</th>
        <th>Isunique</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dwQuestions as $dwQuestion)
        <tr>
            <td>{!! $dwQuestion->id !!}</td>
            <td>{!! $dwQuestion->projectId !!}</td>
            <td>{!! $dwQuestion->xformQuestionId !!}</td>
            <td>{!! $dwQuestion->questionId !!}</td>
            <td>{!! $dwQuestion->path !!}</td>
            <td>{!! $dwQuestion->labelDefault !!}</td>
            <td>{!! $dwQuestion->labelFr !!}</td>
            <td>{!! $dwQuestion->labelUs !!}</td>
            <td>{!! $dwQuestion->dataType !!}</td>
            <td>{!! $dwQuestion->dataFormat !!}</td>
            <td>{!! $dwQuestion->order !!}</td>
            <td>{!! $dwQuestion->linkedIdnr !!}</td>
            <td>{!! $dwQuestion->periodType !!}</td>
            <td>{!! $dwQuestion->periodTypeFormat !!}</td>
            <td>{!! $dwQuestion->isUnique !!}</td>
            <td>
                {!! Form::open(['route' => ['dwsync.dwQuestions.destroy', $dwQuestion->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dwsync.dwQuestions.show', [$dwQuestion->id]) !!}"
                       class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @can('dwsync_create_project')
                        <a href="{!! route('dwsync.dwQuestions.edit', [$dwQuestion->id]) !!}"
                           class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    @endcan
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>