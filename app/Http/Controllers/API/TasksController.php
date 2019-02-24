<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Task;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use JWTAuth;
use Response;
use Validator;
use Log;

class TasksController extends Controller
{

    private $user;

    public function __construct()
    {
        $this->user = JWTAuth::toUser(JWTAuth::getToken());
    }

    public function index()
    {
        if (Gate::allows('admin-only', $this->user)) {
            $tasks = Task::get();
        } else {
            $tasks = Task::where('user_id', $this->user->id)->get();
        }
        Log::channel('user_log')->info(sprintf('User %s got TO-DO list', $this->user->id));
        return response()->json([
            'status' => 'success',
            'data' => compact('tasks')
        ]);
    }

    public function show($id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            return response()->json([
                'status' => 'error',
                'message' => "Task not found."
            ]);
        }
        if (Gate::allows('user-task', $task) || Gate::allows('admin-only', $this->user)) {
            Log::channel('user_log')->info(sprintf('User %s got TO-DO item %s', $this->user->id, $task->id));
            return response()->json([
                'status' => 'success',
                'data' => compact('task')
            ]);
        }
        Log::channel('user_log')->info(sprintf('User %s has not permission to access to TO-DO item', $this->user->id));
        return response()->json([
            'status' => 'error',
            'message' => "You have not permission to access."
        ]);
    }

    public function create(Request $request)
    {
        $credentials = $request->only('title', 'description');
        $rules = [
            'title' => 'required',
            'description' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ]);
        }
        $task = new Task();
        $task->user_id = $this->user->id;
        $task->title = $request['title'];
        $task->description = $request['description'];
        $task->save();
        Log::channel('user_log')->info(sprintf('User %s is created TO-DO item %s', $this->user->id, $task->id));
        return response()->json([
            'status' => 'success',
            'data' => compact('task')
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            return response()->json([
                'status' => 'error',
                'message' => "Task not found."
            ]);
        }
        if (Gate::allows('user-task', $task)) {
            $task->title = $request['title'];
            $task->description = $request['description'];
            $task->save();
            Log::channel('user_log')->info(sprintf('User %s is updated TO-DO item %s', $this->user->id, $task->id));
            return response()->json([
                'status' => 'success',
                'data' => compact('task')
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "You have not permission to access."
            ]);
        }
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            return response()->json([
                'status' => 'error',
                'message' => "Task not found."
            ]);
        }
        if (Gate::allows('admin-only', $this->user)) {
            $task->delete();
            return response()->json([
                'status' => 'success',
                'message' => "Task deleted."
            ]);
        } else {
            Log::channel('user_log')->info(sprintf('User %s has not permission to delete to TO-DO item %s', $this->user->id, $task->id));
            return response()->json([
                'status' => 'error',
                'message' => "You have not permission to access."
            ]);
        }
    }
}

