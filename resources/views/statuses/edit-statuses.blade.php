
@extends('layouts.main')

@section('content')
    <h1>Edit Statuses</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('statuses.update', optional($item)->id) }}' method='POST'>
                @csrf
                @method('POST')

                <div class='form-group'>
                    <label for='name'>Name</label>
                    <input type='text' class='form-control' id='name' name='name'
                        value='{{ optional($item)->name }}' required>
                </div>

                <div class='form-group'>
                    <label for='icon'>Icon</label>
                    <input type='text' class='form-control' id='icon' name='icon'
                        value='{{ optional($item)->icon }}' required>
                </div>

                <div class='form-group'>
                    <label for='color'>Color</label>
                    <input
                        type='color'
                        class='form-control form-control-color'
                        id='color'
                        name='color'
                        value='{{ optional($item)->color ?? "#ffffff" }}'
                        required>
                </div>

                <button type='submit' class='mt-3 btn btn-primary'>Update</button>
            </form>
        </div>
    </div>
@endsection
