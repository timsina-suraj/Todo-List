@extends('layouts.app')

@section('title', 'Todo List')

@section('content')
    <div class="topbar">
        <div class="tabs">
            <a href="{{ route('todos.index', ['status' => 'all']) }}" class="tab {{ $status === 'all' ? 'active' : '' }}">All</a>
            <a href="{{ route('todos.index', ['status' => 'active']) }}" class="tab {{ $status === 'active' ? 'active' : '' }}">Active ({{ $activeCount }})</a>
            <a href="{{ route('todos.index', ['status' => 'completed']) }}" class="tab {{ $status === 'completed' ? 'active' : '' }}">Completed</a>
        </div>
        <a href="{{ route('todos.create') }}" class="btn btn-primary">+ New Todo</a>
    </div>

    @if ($todos->isEmpty())
        <div class="card empty">No todos here. Enjoy the quiet, or add one above.</div>
    @else
        <ul class="todo-list">
            @foreach ($todos as $todo)
                <li class="todo-item {{ $todo->completed ? 'completed' : '' }}">
                    <form class="checkbox-form" method="POST" action="{{ route('todos.toggle', $todo) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="checkbox" onchange="this.form.submit()" @checked($todo->completed) title="Mark as {{ $todo->completed ? 'active' : 'completed' }}">
                    </form>

                    <div class="todo-main">
                        <p class="todo-title">{{ $todo->title }}</p>
                        @if ($todo->description)
                            <p class="todo-desc">{{ $todo->description }}</p>
                        @endif
                        <div class="todo-meta">
                            <span class="badge badge-{{ $todo->priority }}">{{ ucfirst($todo->priority) }}</span>
                            @if ($todo->due_date)
                                <span class="due">Due {{ $todo->due_date->format('M j, Y') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="todo-actions">
                        <a href="{{ route('todos.edit', $todo) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form class="inline" method="POST" action="{{ route('todos.destroy', $todo) }}" onsubmit="return confirm('Delete this todo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
