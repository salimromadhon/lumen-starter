<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\TodoItem;
use App\Todo;

class TodoItemController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        $todo = Todo::find($request->input('todo_id'));

        if ($todo != null and Gate::allows('manage-todo', $todo))
        {
            $todoItem = TodoItem::create([
                'name' => $request->input('name'),
                'todo_id' => $todo->id,
                'user_id' => $user->id,
                'order' => count($todo->todo_items)
            ]);

            return response([
                'status' => 'success',
                'message' => $todoItem
            ]);
        }

        return response([
            'status' => 'failed',
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $todoItem = TodoItem::find($id);

        if ($todoItem != null and Gate::allows('manage-todo-item', $todoItem))
        {
            $input = $request->all();

            foreach ($input as $key => $value)
                if (isset($todoItem->{$key}))
                    $todoItem->{$key} = $value;

            $todoItem->save();

            return response([
                'status' => 'success',
                'message' => $todoItem
            ]);
        }

        return response([
            'status' => 'failed',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $todoItem = TodoItem::find($id);

        if ($todoItem != null and Gate::allows('manage-todo-item', $todoItem))
        {
            $todoItem->delete();

            return response([
                'status' => 'success',
                'message' => $todoItem
            ]);
        }

        return response([
            'status' => 'failed',
        ]);
    }
}
