
@extends('layouts.main')

@section('content')
    <h1>Edit Statusreports</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('statusreports.update', optional($item)->id) }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Title</label>
            <input type='text' class='form-control' id='title' name='title' value='{{ optional($item)->title }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Basis</label>
            <input type='text' class='form-control' id='basis' name='basis' value='{{ optional($item)->basis }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Total_sites</label>
            <input type='text' class='form-control' id='total_sites' name='total_sites' value='{{ optional($item)->total_sites }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Done</label>
            <input type='text' class='form-control' id='done' name='done' value='{{ optional($item)->done }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Cant_be_reached</label>
            <input type='text' class='form-control' id='cant_be_reached' name='cant_be_reached' value='{{ optional($item)->cant_be_reached }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Offline</label>
            <input type='text' class='form-control' id='offline' name='offline' value='{{ optional($item)->offline }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Not_found</label>
            <input type='text' class='form-control' id='not_found' name='not_found' value='{{ optional($item)->not_found }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Users_id</label>
            <input type='text' class='form-control' id='users_id' name='users_id' value='{{ optional($item)->users_id }}' required>
        </div>
    
                <button type='submit' class='mt-3 btn btn-primary'>Update</button>
            </form>
        </div>
    </div>

@endsection
