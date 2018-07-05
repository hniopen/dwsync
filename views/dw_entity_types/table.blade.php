<table class="table table-responsive" id="dwEntityTypes-table">
    <thead>
    <tr>
        <th>Type</th>
        <th>Comment</th>
        <th>Apiurl</th>
        <th colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dwEntityTypes as $dwEntityType)
        <tr>
            <td>{!! $dwEntityType->type !!}</td>
            <td>{!! $dwEntityType->comment !!}</td>
            <td><?php echo config('dwsync.dwBaseUrl').$dwEntityType->apiUrl ?></td>
            <td>
                {!! Form::open(['route' => ['dwsync.dwEntityTypes.destroy', $dwEntityType->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dwsync.dwEntityTypes.show', [$dwEntityType->id]) !!}"
                       class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    @can('dwsync_create_project')
                        <a href="{!! route('dwsync.dwEntityTypes.edit', [$dwEntityType->id]) !!}"
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
