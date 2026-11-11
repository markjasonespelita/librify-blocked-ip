
@extends('layouts.main')

@section('content')
    <h1>Create a new statuses</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('statuses.store') }}' method='POST'>
                @csrf

                <div class='form-group'>
                    <label for='name'>Name</label>
                    <input type='text' class='form-control' id='name' name='name' required>
                </div>

                <div class='form-group'>
                    <label for='icon'>Icon</label>
                    <input type='text' class='form-control' id='icon' name='icon' required>
                </div>

                <div class='form-group'>
                    <label for='color'>Color</label>
                    <input
                        type='color'
                        class='form-control form-control-color'
                        id='color'
                        name='color'
                        value='#ffffff'
                        required
                    >
                </div>

                <button type='submit' class='mt-3 btn btn-primary'>Create</button>
            </form>
        </div>
    </div>
@endsection
