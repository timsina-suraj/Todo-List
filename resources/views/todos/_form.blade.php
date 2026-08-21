<div class="field">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="{{ old('title', $todo->title ?? '') }}" required autofocus>
</div>

<div class="field">
    <label for="description">Description</label>
    <textarea id="description" name="description">{{ old('description', $todo->description ?? '') }}</textarea>
</div>

<div class="field">
    <label for="due_date">Due date</label>
    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', optional($todo->due_date ?? null)->format('Y-m-d')) }}">
</div>

<div class="field">
    <label for="priority">Priority</label>
    <select id="priority" name="priority">
        @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label)
            <option value="{{ $value }}" @selected(old('priority', $todo->priority ?? 'medium') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
