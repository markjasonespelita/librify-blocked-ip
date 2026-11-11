@extends('layouts.main')

@section('content')
    <h1>Edit Site</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sites.update', optional($item)->id) }}" method="POST">
                @csrf
                @method('POST')

                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text"
                           class="form-control"
                           id="name"
                           name="name"
                           value="{{ old('name', optional($item)->name) }}"
                           required>
                </div>

                <div class="form-group mb-3">
                    <label for="municipalities_id">Municipality</label>
                    <select class="form-select"
                            id="municipalities_id"
                            name="municipalities_id"
                            required>
                        <option value="" disabled>Select a municipality</option>

                        @forelse(App\Models\Municipalities::all() as $value)
                            <option value="{{ $value->id }}"
                                {{ old('municipalities_id', optional($item)->municipalities_id) == $value->id ? 'selected' : '' }}>
                                {{ $value->name }}
                            </option>
                        @empty
                            <option value="" disabled>No municipalities available</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="olts_id">OLT Area</label>
                    <select class="form-select"
                            id="olts_id"
                            name="olts_id"
                            required>
                        <option value="" disabled>Select an OLT area</option>

                        @forelse(App\Models\Olts::all() as $value)
                            <option value="{{ $value->id }}"
                                {{ old('olts_id', optional($item)->olts_id) == $value->id ? 'selected' : '' }}>
                                {{ $value->name }}
                            </option>
                        @empty
                            <option value="" disabled>No OLT available</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="statuses_id">Status</label>
                    <select class="form-select"
                            id="statuses_id"
                            name="statuses_id"
                            required>
                        <option value="" disabled>Select a status</option>

                        @forelse(App\Models\Statuses::all() as $value)
                            <option value="{{ $value->id }}"
                                {{ old('statuses_id', optional($item)->statuses_id) == $value->id ? 'selected' : '' }}>
                                {{ $value->name }} {{ $value->icon ?? '' }}
                            </option>
                        @empty
                            <option value="" disabled>No status available</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="ip_address">IP Address / URL</label>
                    <input type="text"
                           class="form-control"
                           id="ip_address"
                           name="ip_address"
                           value="{{ old('ip_address', optional($item)->ip_address) }}"
                           required>
                </div>

                <div class="form-group mb-3">
                    <label for="remarks">Remarks</label>
                    <input type="text"
                           class="form-control"
                           id="remarks"
                           name="remarks"
                           value="{{ old('remarks', optional($item)->remarks) }}"
                           required>
                </div>

                <button type="submit" class="mt-3 btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>

                <a href="{{ route('sites.index') }}" class="mt-3 btn btn-secondary">
                    Cancel
                </a>
            </form>
        </div>
    </div>
@endsection
