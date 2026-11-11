
@extends('layouts.main')

@section('content')
    <h1>Create a new olts</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('olts.store') }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='name' required>
        </div>
    
                <button type='submit' class='mt-3 btn btn-primary'>Create</button>
            </form>
        </div>
    </div>

@endsection
