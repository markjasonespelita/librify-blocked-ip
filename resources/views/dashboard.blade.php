@extends('layouts.main')

@section('content')
    <h1>Dashboard</h1>
    <b class="top-b">Hello, {{ Auth::user()->name }} ({{ Auth::user()->getRoleNames()->implode(', ') }})</b>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Sites</h5>
                    <h1 class="dashboard-h1">
                        <i class="fas fa-location-crosshairs"></i> {{ App\Models\Sites::count() }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Statuses</h5>
                    <h1 class="dashboard-h1">
                        <i class="fas fa-exclamation-triangle"></i> {{ App\Models\Statuses::count() }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total OLTs</h5>
                    <h1 class="dashboard-h1">
                        <i class="fas fa-wifi"></i> {{ App\Models\Olts::count() }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Municipalities</h5>
                    <h1 class="dashboard-h1">
                        <i class="fas fa-map-marker"></i> {{ App\Models\Municipalities::count() }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('assets/jquery/jquery.min.js') }}"></script>
@endsection
