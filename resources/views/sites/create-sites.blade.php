
@extends('layouts.main')

@section('content')
    <h1>Create a new sites</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('sites.store') }}' method='POST'>
                @csrf

        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='name' required>
        </div>

        <div class='form-group'>
            <label for='name'>Municipality</label>
            <select class="form-select" id="location" name="municipalities_id">
                <option value="" selected disabled>Select a location</option>
                @forelse(App\Models\Municipalities::all() as $key => $value)
                    <option value="{{ $value->id }}">
                        {{ $value->name }}
                    </option>
                @empty
                    <option value="" disabled>No municipalities available</option>
                @endforelse
            </select>
        </div>

        <div class='form-group'>
            <label for='name'>OLT Area</label>
            <select class="form-select" id="location" name="olts_id">
                <option value="" selected disabled>Select a location</option>
                @forelse(App\Models\Olts::all() as $key => $value)
                    <option value="{{ $value->id }}">
                        {{ $value->name }}
                    </option>
                @empty
                    <option value="" disabled>No OLT available</option>
                @endforelse
            </select>
        </div>

        <div class='form-group'>
            <label for='name'>Status</label>
            <select class="form-select" id="location" name="statuses_id">
                @forelse(App\Models\Statuses::all() as $key => $value)
                    <option value="{{ $value->id }}">
                        {{ $value->name }}
                    </option>
                @empty
                    <option value="" disabled>No OLT available</option>
                @endforelse
            <select class="form-select" id="location" name="location">
        </div>

        <div class='form-group'>
            <label for='name'>Ip_address</label>
            <input type='text' class='form-control' id='ip_address' name='ip_address' required>
        </div>

        <div class='form-group'>
            <label for='name'>Remarks</label>
            <input type='text' class='form-control' id='remarks' name='remarks' required>
        </div>

                <button type='submit' class='mt-3 btn btn-primary'>Create</button>
            </form>
        </div>
    </div>

@endsection
