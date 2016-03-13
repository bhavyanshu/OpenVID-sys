<?php
/**
 * OpenVID-sys
 *
 * @copyright 2016 Bhavyanshu Parasher (https://bhavyanshu.me)
 * @license   http://www.apache.org/licenses/LICENSE-2.0.txt Apache License
 * @link      https://bhavyanshu.me/pages/openvid_sys/
 */
namespace openvidsys\Http\Controllers\Auth;

use Validator;
use Hash;
use DB;
use File;
use Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use openvidsys\User;
use openvidsys\Model\ResProfile;
use openvidsys\Model\OrgProfile;
use openvidsys\Common\Utility;
use openvidsys\Http\Controllers\Controller;

/**
 * Controller for Authentication related methods
 */
class AuthController extends Controller
{
    /**
     * User model instance
     *
     * @var User
     */
    protected $user;

    /**
     * For Guard
     *
     * @var Authenticator
     */
    protected $auth;

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'user/dashboard';

    /**
     * Create a new controller instance.
     *
     * @param Guard $auth
     * @param User $user
     *
     * @return void
     */
    public function __construct(Guard $auth, User $user)
    {
        $this->user = $user;
        $this->auth = $auth;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
          'username' => 'required|max:255|unique:users',
          'email' => 'required|email|max:255|unique:users',
          'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * GET Non-authenticated user welcome page.
     */
    protected function getWelcome() {
      return view('noauth.welcome');
    }

    /**
     * GET Registration view for organization/vendor.
     */
    protected function getORegister() {
      return View('users.org.register');
    }

    /**
     * GET Registration view for researcher.
     */
    protected function getRRegister() {
      return View('users.researcher.register');
    }

    /**
     * POST Register the user.
     *
     * @param  Request $request
     *
     * @return redirect
     */
    protected function postRegister(Request $request) {
      $roleid = $request->_u_ro_id;
      if($roleid == 2) { //register org
        $rules = array(
          'username' => 'required|max:255|unique:users',
          'email' => 'required|email|max:255|unique:users',
          'password' => 'required|confirmed|min:6',
          'password_confirmation'=>'required',
          'first_name' => 'required',
          'last_name' => 'required',
          'display_name' => 'required',
          'legal_name' => 'required'
        );
        $messages = [
            'username.unique' => 'This username is already taken',
            'email.unique'  => 'An account already exists with this email address'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes())
        {
          $confirmation_code = hash_hmac('sha256', str_random(40), str_random(10));
          $this->user->confirmation_code = $confirmation_code;
          $this->user->username = $request->username;
          $this->user->email = $request->email;
          $this->user->password = bcrypt($request->password);
          $this->user->role_id = $roleid;
          $this->user->save();

          if ($this->auth->attempt($request->only('email', 'password'))) {
              if ($request->user()) {
                $user = $request->user();

                $profile = new OrgProfile;
                $profile->user()->associate($user);
                $profile->display_name = ucfirst($request->display_name);
                $profile->legal_name = ucfirst($request->legal_name);
                $profile->first_name = ucfirst($request->first_name);
                $profile->last_name = ucfirst($request->last_name);
                $profile->save();

                $confirmcode = array('confirmcode' => $confirmation_code);
                \Mail::send('emails.confirmation', $confirmcode, function($message) use ($request) {
                    $message->to($this->user->email, ucfirst($request->first_name).' '.ucfirst($request->last_name))
                        ->subject('Welcome '.ucfirst($request->first_name).', Verify your email address');
                });
              }
              return redirect()->route('dashboard')->with('message','Verification email has been sent to you. Please check your email account.');
          }
          else {
            return redirect('/')->with('message','Verification email has been sent to you. Please check your email account.');
          }
        }
        else {
          return redirect()->back()->withInput()
            ->withErrors($validator);
        }
      }
      elseif($roleid == 3) { // register res
        $rules = array(
          'username' => 'required|max:255|unique:users',
          'email' => 'required|email|max:255|unique:users',
          'password' => 'required|confirmed|min:6',
          'password_confirmation'=>'required',
          'first_name' => 'required',
          'last_name' => 'required',
        );
        $messages = [
            'username.unique' => 'This username is already taken',
            'email.unique'  => 'An account already exists with this email address'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes())
        {
          $confirmation_code = hash_hmac('sha256', str_random(40), str_random(10));
          $this->user->confirmation_code = $confirmation_code;
          $this->user->username = $request->username;
          $this->user->email = $request->email;
          $this->user->password = bcrypt($request->password);
          $this->user->role_id = $roleid;
          $this->user->save();

          if ($this->auth->attempt($request->only('email', 'password'))) {
              if ($request->user()) {
                $user = $request->user();

                $profile = new ResProfile;
                $profile->user()->associate($user);
                $profile->first_name = ucfirst($request->first_name);
                $profile->last_name = ucfirst($request->last_name);
                $profile->save();

                $confirmcode = array('confirmcode' => $confirmation_code);
                \Mail::send('emails.confirmation', $confirmcode, function($message) use ($request) {
                    $message->to($this->user->email, ucfirst($request->first_name).' '.ucfirst($request->last_name))
                        ->subject('Welcome '.ucfirst($request->first_name).', Verify your email address');
                });
              }
              return redirect()->route('dashboard')->with('message','Verification email has been sent to you. Please check your email account.');
          }
          else {
            return redirect('/')->with('message','Verification email has been sent to you. Please check your email account.');
          }
        }
        else {
          return redirect()->back()->withInput()
            ->withErrors($validator);
        }
      }
    }

    /**
     * GET Verify user's email address.
     *
     * @param  String $confirmcode
     */
    protected function verifyEmail($confirmcode) {
      if(!$confirmcode)
      {
          return redirect()->route('/');
      }
      $user = User::whereConfirmationCode($confirmcode)->first();
      if (!$user)
      {
          $message = "Invalid confirmation code. Copy/Paste the url sent in your mail carefully. It's better to just click the link in mail and open in browser.";
          if(Auth::check()) {
            return redirect('user/dashboard')->with('message',$message);
          }
          else {
            //dd($message);
            return redirect('/')->with('message',$message);
          }
      }
      else
      {
          $user->confirmed = 1;
          $user->blocked = 0;
          $user->save();
          $pathcheck = public_path().'/user/uploads/'.$user->username;
          if(!File::exists($pathcheck)) {
            File::makeDirectory($pathcheck, 0775, true); //public access
          }
          Storage::makeDirectory('userfiles/uploads/'.$user->username); //auth access
          $message = 'You have successfully verified your account.';
          if(Auth::check()) {
            return redirect('user/profile/edit')->with('message','You have successfully verified your account. Kindly update information below to get complete access.');
          }
          else {
            return redirect('/')->with('message',$message);
          }
      }
    }

    /**
     * GET Load view to show change password form.
     */
    protected function showPasschangeform() {
      $profile = Utility::getProfile(Auth::user());
      if (Auth::user()->role_id==2) {
        return view('users.org.profile.password_change')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==3) {
        return view('users.researcher.profile.password_change')->with('profile', $profile);
      }
    }

    /**
     * POST Set new password post authentication
     *
     * @param  Request $request
     *
     * @return redirect
     */
    protected function postAuthReset(Request $request) {
      $rules = array(
          'password'=>'required|confirmed',
          'password_confirmation'=>'required'
      );
      $validator = Validator::make($request->all(), $rules);

      if ($validator->passes())
      {
          $newpassword=$request->input('password');
          $passw= Hash::make($newpassword);
          $user = $request->user();
          $userid = $user->id;
          DB::table('users')
          ->where('id', $userid)
          ->update(array('password'=>$passw,'confirmed'=>1));
          return redirect()->route('dashboard')->with('message', 'Your password has been updated!.');
      }
      return redirect()->route('password_change')
        ->withInput()
        ->withErrors($validator);
    }
}
