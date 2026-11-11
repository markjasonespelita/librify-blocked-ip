
@extends('layouts.main')

@section('content')
    <h1>Edit Statuses</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('statuses.update', optional($item)->id) }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='name' value='{{ optional($item)->name }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Icon</label>
            <input type='text' class='form-control' id='icon' name='icon' value='{{ optional($item)->icon }}' required>
        </div>
    
                <button type='submit' class='mt-3 btn btn-primary'>Update</button>
            </form>
        </div>
    </div>

@endsection
