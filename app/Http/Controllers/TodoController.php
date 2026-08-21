<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $todos = Todo::query()
            ->when($status === 'active', fn ($query) => $query->where('completed', false))
            ->when($status === 'completed', fn ($query) => $query->where('completed', true))
            ->orderBy('completed')
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('due_date')
            ->get();

        return view('todos.index', [
            'todos' => $todos,
            'status' => $status,
            'activeCount' => Todo::where('completed', false)->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request): RedirectResponse
    {
        Todo::create($request->validated());

        return redirect()->route('todos.index')->with('status', 'Todo created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo): View
    {
        return view('todos.edit', ['todo' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo): RedirectResponse
    {
        $todo->update($request->validated());

        return redirect()->route('todos.index')->with('status', 'Todo updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo): RedirectResponse
    {
        $todo->delete();

        return redirect()->route('todos.index')->with('status', 'Todo deleted.');
    }

    /**
     * Toggle the completed state of the specified resource.
     */
    public function toggle(Todo $todo): RedirectResponse
    {
        $todo->update(['completed' => ! $todo->completed]);

        return redirect()->route('todos.index', ['status' => request('status', 'all')]);
    }
}
