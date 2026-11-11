
@extends('layouts.main')

@section('content')
    <h1>Statuses Details</h1>

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
                        <th>Icon</th>
                        <td>{{ optional($item)->icon }}</td>
                    </tr>
                    <tr>
                        <th>Color</th>
                        <td>
                            <div class='d-flex align-items-center'>
                                <span style="
                                    display: inline-block;
                                    width: 30px;
                                    height: 30px;
                                    background-color: {{ optional($item)->color ?? '#ffffff' }};
                                    border: 1px solid #ccc;
                                    border-radius: 4px;
                                    margin-right: 10px;
                                "></span>
                                <code>{{ optional($item)->color ?? '#ffffff' }}</code>
                            </div>
                        </td>
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

    <a href='{{ route('statuses.index') }}' class='btn btn-primary'>
        <i class='fas fa-arrow-circle-left text-light'></i>
    </a>

    @can('can edit statuses')
        <a href='{{ route('statuses.edit', optional($item)->id) }}' class='btn btn-info'>
            <i class='fas fa-edit text-light'></i>
        </a>
    @endcan

    @can('can delete statuses')
        <a href='{{ route('statuses.delete', optional($item)->id) }}' class='btn btn-danger'>
            <i class='fas fa-trash text-light'></i>
        </a>
    @endcan
@endsection
