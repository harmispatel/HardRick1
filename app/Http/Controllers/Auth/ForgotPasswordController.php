<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Str;
use Hash;
use DB;   
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


     public function showForgetPassword(Request $request)
      {
        
          return view('auth.passwords.forgetpassword');
      }

         /**
         * Write code on check User Email Authenticated
         *
         * @return response()
         */
        public function submitForgetPassword(Request $request)
        {
            $request->validate([    
                'email' => 'required|email|exists:users',
            ]);
            
            $token = Str::random(10);
            $data['email']=$request->email;
            $data['token']=$token;
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        
            try {
               Mail::send(
                'auth.passwords.sendlink',
                ['token' => $data['token']],
            function ($message) use ($data) {
                $message->from('harmistest@gmail.com');
                $message->to($data['email']);
                $message->subject('Reset Password');
            });
                $user = User::where('email', $request->email)
                ->update(['email_token' => $token]);
            } catch (\Throwable $th) {
                dd($th);
                return back()->with('error', 'Failed to send an Email');
            }

            return back()->with('success', 'We have e-mailed your password reset link!');
        }

          /**
     * Write code on Send User Email Forget Password Link
     *
     * @return response()
     */
    public function showResetPassword($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

      /**
         * Write code on User Reset Password
         *
         * @return response()
         */
        public function submitResetPassword(Request $request)
        {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|same:password'
            ]);
            
            $updatePassword = DB::table('users')
            ->where([
                'email_token' => $request->token
                ])->first();
                
            if (!$updatePassword) {
                
                return back()->withInput()->with('error', 'Invalid token!');
            }
            // User Update Password
            $user = User::where('email_token', $request->token)
                        ->update(['password' => bcrypt($request->password)]);
    
            DB::table('password_resets')->where(['token'=> $request->token])->delete();
    
            return redirect('login')->with('success', 'Your password has been changed!');
        }

}
