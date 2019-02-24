<?php
/**
 * Created by PhpStorm.
 * User: Oscar
 * Date: 24.02.2019
 * Time: 14:59
 */

namespace App\Http\Controllers\Auth;

use DB;
use Auth;
use Hash;
use Log;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class PasswordController
{
    public function sendPasswordResetToken(Request $request)
    {
        Log::channel('user_log')->info("Send Password Reset Token. Email: {$request['email']}");
        $user = User::where('email', $request['email'])->first();
        if (!$user) {
            Log::channel('user_log')->info(sprintf("User not found. Email %s", $request['email']));
            return view('auth.password', ['errors' => array('Vartotojas su tokiu elektroniniu neegzistuoja.')]);
        }

        //create a new token or update to be sent to the user.
        $reset = DB::table('password_resets')->where('email', $request->email)->first();
        if(empty($reset)){
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => str_random(60), //change 60 to any length you want
                'created_at' => Carbon::now()
            ]);
        } else {
            DB::table('password_resets')->where('email', $request->email)->update([
                'token' => str_random(60), //change 60 to any length you want
                'created_at' => Carbon::now()
            ]);
        }

        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

        $token = $tokenData->token;
        $this->sendEmail($user->id, $token);

        return view('auth.reset-success');
    }

    public function sendEmail($id, $token)
    {
        $user = User::findOrFail($id);

        Mail::send('auth.reset-mail', ['user' => $user, 'token' => $token], function ($m) use ($user) {
            $m->from('noreply@'.$_SERVER['HTTP_HOST'], 'R.I.T.A.');

            $m->to($user->email, $user->name)->subject('Slaptažodžio atstatymas.');
        });
    }

    public function showForm() {
        return view('auth.password');
    }

    public function showPasswordResetForm($token)
    {
        $tokenData = DB::table('password_resets')
            ->where('token', $token)->first();

        if (!$tokenData) return redirect()->to('/');
        return view('auth.reset-form', ['token' => $token]);
    }

    public function resetPassword(Request $request, $token)
    {
        $credentials = $request->only('password', 'password_confirmation');
        $rules = [
            'password' => 'required|min:6|confirmed'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return view('auth.reset-form', ['errors' => $validator->messages(), 'token' => $token]);
        }

        $password = $request['password'];
        $tokenData = DB::table('password_resets')
            ->where('token', $token)->first();

        $user = User::where('email', $tokenData->email)->first();
        if (!$user) return redirect()->to('/'); //or wherever you want

        $user->password = app('hash')->make($password);
        $user->update();

        Log::channel('user_log')->info("Password Reset For User id: {$user->id}");

        // If the user shouldn't reuse the token later, delete the token
        DB::table('password_resets')->where('email', $user->email)->delete();

        //redirect where we want according to whether they are logged in or not.
        return redirect()->to('/'); //or wherever you want
    }
}