<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Todo;

class TodoController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $user = $request->user();

        if ($id != null)
            $todo = Todo::where([
                'id' => $id,
                'user_id' => $user->id
            ])->with('todo_items')->first();
        else
            $todo = $user->todos;

        return response([
            'status' => 'success',
            'message' => $todo
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $todo = Todo::create([
            'title' => $request->input('title'),
            'user_id' => $user->id
        ]);

        return response([
            'status' => 'success',
            'message' => $todo
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $todo = Todo::find($id);

        if ($todo != null and Gate::allows('manage-todo', $todo))
        {
            $input = $request->all();

            foreach ($input as $key => $value)
                if (isset($todo->{$key}))
                    $todo->{$key} = $value;

            $todo->save();

            return response([
                'status' => 'success',
                'message' => $todo
            ]);
        }

        return response([
            'status' => 'failed',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $todo = Todo::find($id);

        if ($todo != null and Gate::allows('manage-todo', $todo))
        {
            $todo->delete();

            return response([
                'status' => 'success',
                'message' => $todo
            ]);
        }

        return response([
            'status' => 'failed',
        ]);
    }
}
