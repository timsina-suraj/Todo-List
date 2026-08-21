@extends('layouts.app')

@section('title', 'Edit Todo')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('todos.update', $todo) }}">
            @csrf
            @method('PUT')

            @include('todos._form')

            <div class="field">
                <label>
                    <input type="checkbox" name="completed" value="1" @checked(old('completed', $todo->completed))>
                    Completed
                </label>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('todos.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
