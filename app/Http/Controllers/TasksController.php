<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Task;
use Auth;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = Task::where('user_id', Auth::user()->id)
                 ->orderBy('deadline', 'asc')
                 ->get();
        // ddd($tasks);
        return view('tasks', [
          'tasks' => $tasks,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // ddd($request);
        //
        $validator = Validator::make($request->all(), [
          'task' => 'required|max:255',
          'deadline' => 'required',
        ]);
        
        $validator = Validator::make($request->all(), [
           'task' => 'required|max:255',
           'deadline' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
              ->route('tasks.index')
              ->withInput()
              ->withErrors($validator);
        }
        // Eloquent
        $task = new Task;
        $task->user_id = Auth::user()->id;
        $task->task = $request->task;
        $task->deadline = $request->deadline;
        $task->comment = $request->comment;
        $task->save();
        // tasks.index
        return redirect()->route('tasks.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $task = Task::find($id);
        return view('taskedit', [
          'task' => $task
          ]);
    }

    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
          'task' => 'required|max:255',
          'deadline' => 'required',
        ]);
        // :
        if ($validator->fails()) {
            return redirect()
              ->route('tasks.edit', $id)
              ->withInput()
              ->withErrors($validator);
        }
        //
        $task = Task::find($id);
        $task->task = $request->task;
        $task->deadline = $request->deadline;
        $task->comment = $request->comment;
        $task->save();
        return redirect()->route('tasks.index');
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        // ddd($task);
        $task->delete();
        return redirect()->route('tasks.index');
    }
}
