<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Log;
use Validator;
use Gate;
use App\Task;

class AuthController extends Controller
{

    /**
     * API Login, on success return JWT Auth token
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if ($validator->fails()) {
            return view('wellcome', ['errors' => $validator->messages()]);
        }
        try {
            // Attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return view('wellcome', ['errors' => array('We can`t find an account with this credentials.')]);
            }
        } catch (JWTException $e) {
            // Something went wrong with JWT Auth.
            return view('wellcome', ['errors' => array('Failed to login, please try again.')]);
        }
        // All good so return the token
        return redirect('profile/' . $token);
//        return view('front.profile', ['token' => $token]);
    }

    /**
     * Logout
     * Invalidate the token. User have to relogin to get a new token.
     * @param Request $request 'header'
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Get JWT Token from the request header key "Authorization"
        $token = $request->header('Authorization');
        // Invalidate the token
        try {
            JWTAuth::invalidate($token);
            return view('wellcome', ['errors' => array('User successfully logged out.')]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return view('front.profile', ['token' => $token, 'errors' => array('Failed to logout, please try again.')]);
        }
    }

    public function profile($token) {
        $user = JWTAuth::user();
        if (Gate::allows('admin-only', $user)) {
            return view('front.admin', ['token' => $token]);
        } else {
            return view('front.profile', ['token' => $token]);
        }

    }

    public function destroy(Request $request, $id){
        {
            $task = Task::find($id);
            $user = JWTAuth::user();
            if (empty($task)) {
                return view('front.admin', ['token' => $request['token'], 'errors' => array('Task not found.')]);
            }
            if (Gate::allows('admin-only', $user)) {
                $task->delete();
                return redirect('profile/' . $request['token']);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => "You have not permission to access."
                ]);
            }
        }
    }

    public function create(Request $request){
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
        $user = JWTAuth::user();
        $task = new Task();
        $task->user_id = $user->id;
        $task->title = $request['title'];
        $task->description = $request['description'];
        $task->save();
        return redirect('profile/' . $request['token']);
    }

    public function edit(Request $request, $id) {
        $user = JWTAuth::user();
        $task = Task::find($id);
        if (Gate::allows('user-task', $task)) {
            return view('front.edit', ['task' => $task, 'token' => $request['token']]);
        }
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        $user = JWTAuth::user();
        if (empty($task)) {
            return redirect('profile/' . $request['token']);
        }
        if (Gate::allows('user-task', $task)) {
            $task->title = $request['title'];
            $task->description = $request['description'];
            $task->save();
            Log::channel('user_log')->info(sprintf('User %s is updated TO-DO item %s', $user->id, $task->id));
            return redirect('profile/' . $request['token']);

        } else {
            return redirect('profile/' . $request['token']);
        }
    }

}