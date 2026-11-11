
@extends('layouts.main')

@section('content')
    <h1>Site Details</h1>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ optional($item)->id }}</td>
                        </tr>

                        <tr>
                            <th>Name</th>
                            <td>{{ optional($item)->name }}</td>
                        </tr>

                        <tr>
                            <th>Municipality</th>
                            <td>{{ optional($item->municipality)->name ?? 'n/a' }}</td>
                        </tr>

                        <tr>
                            <th>OLT</th>
                            <td>{{ optional($item->olt)->name ?? 'n/a' }}</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                {{ optional($item->status)->name ?? 'n/a' }}
                                {{ optional($item->status)->icon ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <th>IP Address</th>
                            <td>
                                @php
                                    $ip = optional($item)->ip_address;
                                    $accessible = App\Helpers\Network::checkAccessibility($ip);

                                    $url = filter_var($ip, FILTER_VALIDATE_URL)
                                        ? $ip
                                        : 'http://' . $ip;
                                @endphp

                                @if($accessible)
                                    <a href="{{ $url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="badge bg-success text-decoration-none">
                                        {{ $ip }} ONLINE
                                        <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                @else
                                    <span class="badge bg-danger">
                                        {{ $ip ?? 'N/A' }} OFFLINE
                                    </span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Remarks</th>
                            <td>{{ optional($item)->remarks ?? 'n/a' }}</td>
                        </tr>

                        <tr>
                            <th>Created At</th>
                            <td>
                                {{ App\Smark\Dater::humanReadableDateWithDayAndTime(optional($item)->created_at) }}
                            </td>
                        </tr>

                        <tr>
                            <th>Updated At</th>
                            <td>
                                {{ App\Smark\Dater::humanReadableDateWithDayAndTime(optional($item)->updated_at) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href='{{ route('sites.index') }}' class='btn btn-primary'><i class='fas fa-arrow-circle-left text-light'></i></a>
    @can('can edit sites')
        <a href='{{ route('sites.edit', optional($item)->id) }}' class='btn btn-info'><i class='fas fa-edit text-light'></i></a>
    @endcan
    @can('can delete sites')
        <a href='{{ route('sites.delete', optional($item)->id) }}' class='btn btn-danger'><i class='fas fa-trash text-light'></i></a>
    @endcan
@endsection
