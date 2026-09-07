<div class="field">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="{{ old('title', $todo->title ?? '') }}" required maxlength="50" data-todo-validate>
</div>

<div class="field">
    <label for="description">Description</label>
    <textarea id="description" name="description" required maxlength="300" data-todo-validate>{{ old('description', $todo->description ?? '') }}</textarea>
</div>

<div class="field">
    <label for="due_date">Due date</label>
    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', optional($todo->due_date ?? null)->format('Y-m-d')) }}" required min="{{ now()->toDateString() }}" data-todo-validate>
</div>

<div class="field">
    <label for="priority">Priority</label>
    <select id="priority" name="priority" required data-todo-validate>
        <option value="" disabled @selected(old('priority', $todo->priority ?? '') === '')>Select a priority</option>
        @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label)
            <option value="{{ $value }}" @selected(old('priority', $todo->priority ?? '') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
