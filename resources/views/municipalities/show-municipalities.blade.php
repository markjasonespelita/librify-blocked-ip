
@extends('layouts.main')

@section('content')
    <h1>Municipalities Details</h1>

    <div class='card'>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <tr>
                        <th>ID</th>
                        <td>{{ optional($item)->id }}</td>
                    </tr>
                    
        <tr>
            <th>Name</th>
            <td>{{ optional($item)->name }}</td>
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

    <a href='{{ route('municipalities.index') }}' class='btn btn-primary'><i class='fas fa-arrow-circle-left text-light'></i></a>
    @can('can edit municipalities')
        <a href='{{ route('municipalities.edit', optional($item)->id) }}' class='btn btn-info'><i class='fas fa-edit text-light'></i></a>
    @endcan
    @can('can delete municipalities')
        <a href='{{ route('municipalities.delete', optional($item)->id) }}' class='btn btn-danger'><i class='fas fa-trash text-light'></i></a>
    @endcan
@endsection
