
@extends('layouts.main')

@section('content')
    <h1>Statusreports Details</h1>

    <div class='card'>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <tr>
                        <th>ID</th>
                        <td>{{ optional($item)->id }}</td>
                    </tr>
                    
        <tr>
            <th>Title</th>
            <td>{{ optional($item)->title }}</td>
        </tr>
    
        <tr>
            <th>Basis</th>
            <td>{{ optional($item)->basis }}</td>
        </tr>
    
        <tr>
            <th>Total_sites</th>
            <td>{{ optional($item)->total_sites }}</td>
        </tr>
    
        <tr>
            <th>Done</th>
            <td>{{ optional($item)->done }}</td>
        </tr>
    
        <tr>
            <th>Cant_be_reached</th>
            <td>{{ optional($item)->cant_be_reached }}</td>
        </tr>
    
        <tr>
            <th>Offline</th>
            <td>{{ optional($item)->offline }}</td>
        </tr>
    
        <tr>
            <th>Not_found</th>
            <td>{{ optional($item)->not_found }}</td>
        </tr>
    
        <tr>
            <th>Users_id</th>
            <td>{{ optional($item)->users_id }}</td>
        </tr>
    
                    <tr>
                        <th>Created At</th>
                        <td>{{ App\Smark\Dater::humanReadableDateWithDayAndTime(optional($item)->created_at) }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ App\Smark\Dater::humanReadableDateWithDayAndTime(optional($item)->updated_at) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <a href='{{ route('statusreports.index') }}' class='btn btn-primary'><i class='fas fa-arrow-circle-left text-light'></i></a>
    @can('can edit statusreports')
        <a href='{{ route('statusreports.edit', optional($item)->id) }}' class='btn btn-info'><i class='fas fa-edit text-light'></i></a>
    @endcan
    @can('can delete statusreports')
        <a href='{{ route('statusreports.delete', optional($item)->id) }}' class='btn btn-danger'><i class='fas fa-trash text-light'></i></a>
    @endcan
@endsection
