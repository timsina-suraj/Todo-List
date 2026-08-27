<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $todos = Auth::user()->todos()
            ->when($status === 'active',    fn ($query) => $query->where('completed', false))
            ->when($status === 'completed', fn ($query) => $query->where('completed', true))
            ->orderBy('completed')
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('due_date')
            ->get();

        return view('todos.index', [
            'todos'       => $todos,
            'status'      => $status,
            'activeCount' => Auth::user()->todos()->where('completed', false)->count(),
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
        Auth::user()->todos()->create($request->validated());

        return redirect()->route('todos.index')->with('status', 'Todo created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo): View
    {
        abort_if($todo->user_id !== Auth::id(), 403);
        return view('todos.edit', ['todo' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo): RedirectResponse
    {
        abort_if($todo->user_id !== Auth::id(), 403);
        $todo->update($request->validated());

        return redirect()->route('todos.index')->with('status', 'Todo updated.');
    }

    /**
     * Soft-delete the specified resource (keeps it in the database).
     */
    public function destroy(Todo $todo): RedirectResponse
    {
        abort_if($todo->user_id !== Auth::id(), 403);
        $todo->delete(); // soft delete — sets deleted_at, row stays in DB

        return redirect()
            ->route('todos.index', ['status' => request('status', 'all')])
            ->with('deleted_todo_id', $todo->id)
            ->with('deleted_todo_title', $todo->title)
            ->with('status', 'Todo deleted.');
    }

    /**
     * Restore a soft-deleted todo.
     */
    public function restore(int $id): RedirectResponse
    {
        $todo = Auth::user()->todos()->withTrashed()->findOrFail($id);
        $todo->restore();

        return redirect()
            ->route('todos.index', ['status' => request('status', 'all')])
            ->with('status', "'{$todo->title}' has been restored.");
    }

    /**
     * Toggle the completed state of the specified resource.
     */
    public function toggle(Todo $todo): RedirectResponse
    {
        abort_if($todo->user_id !== Auth::id(), 403);
        $todo->update(['completed' => ! $todo->completed]);

        return redirect()->route('todos.index', ['status' => request('status', 'all')]);
    }
}
