<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Support\Facades\Gate;
use JWTAuth;
use Validator;

class AdminController extends Controller
{

    private $user;

    public function __construct()
    {
        $this->user = JWTAuth::toUser(JWTAuth::getToken());
    }

    public function index()
    {
        if (Gate::allows('admin-only', $this->user)) {
            $users = User::get();
            return response()->json([
                'status' => 'success',
                'data' => compact('users')
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => "You have not permission to access."
        ]);
    }

    public function logs()
    {
        if (Gate::allows('admin-only', $this->user)) {
            $logs = file(storage_path('logs/user.log'), FILE_IGNORE_NEW_LINES);
            return response()->json([
                'status' => 'success',
                'data' => compact('logs')
            ]);
//            return view('logs', ['log' => $log]);
        }
        return response()->json([
            'status' => 'success',
            'message' => "You have not permission to access."
        ]);
    }

}