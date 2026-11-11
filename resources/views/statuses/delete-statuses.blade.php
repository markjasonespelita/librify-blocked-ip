
@extends('layouts.main')

@section('content')
    <h1>Are you sure you want to delete this statuses?</h1>

    <form action='{{ route('statuses.destroy', optional($item)->id) }}' method='GET'>
        @csrf
        @method('DELETE')
        <button type='submit' class='btn btn-danger'>Yes, Delete</button>
        <a href='{{ route('statuses.index') }}' class='btn btn-secondary'>Cancel</a>
    </form>
@endsection
