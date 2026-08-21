@extends('layouts.app')

@section('title', 'New Todo')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('todos.store') }}">
            @csrf

            @include('todos._form')

            <div class="actions">
                <button type="submit" class="btn btn-primary">Add Todo</button>
                <a href="{{ route('todos.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
