<?php
/**
 * OpenVID-sys
 *
 * @copyright 2016 Bhavyanshu Parasher (https://bhavyanshu.me)
 * @license   http://www.apache.org/licenses/LICENSE-2.0.txt Apache License
 * @link      https://bhavyanshu.me/pages/openvid_sys/
 */
namespace openvidsys\Http\Controllers\Universal;

use Validator;
use Hash;
use DB;
use File;
use Response;
use Image;
use Notifynder;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;

use openvidsys\User;
use openvidsys\Model\OrgProfile;
use openvidsys\Model\ResProfile;
use openvidsys\Model\Product;
use openvidsys\Model\Vulnerability;
use openvidsys\Common\Utility;
use openvidsys\Http\Controllers\Controller;

/**
 * Controller for Profile related methods.
 */
class ProfileController extends Controller
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
     * GET View to display profile information based on what role is viewing what.
     *
     * @param String $uname
     *
     * @return Response
     */
    protected function view($uname) {
      if(Auth::user()->role_id == 2) { //vendor/org
        if (Auth::user()->username == $uname) { //user viewing own profile
          $loggedin = Auth::user();
          $profile = Utility::getProfile($loggedin);
          $vulns = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')->where('user_p_id','=',$loggedin->id)->orderBy('vulnerabilities.created_at', 'desc')->paginate(10);
          return view('users.org.profile.ownprofile')->with('profile', $profile)->with('vulns',$vulns);
        }
        else { //user viewing someone else's profile
          $loggedin = Auth::user();
          $profile = Utility::getProfile($loggedin);

          $elseuser = User::where('username', $uname)->first();
          $elseprofile = Utility::getProfile($elseuser);

          if($elseuser->role_id == 2) { //vendor views vendor
            $vulns = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')->where('user_p_id','=',$elseuser->id)->orderBy('vulnerabilities.created_at', 'desc')->paginate(10);
            return view('users.org.profile.viewvendorprofile')->with('profile', $profile)->with('vulns',$vulns)->with('elseprofile',$elseprofile)->with('elseuser',$elseuser);
          }
          elseif($elseuser->role_id == 3) { //vendor view researcher
            $vulns = Vulnerability::with('product')->where('user_vul_author_id','=',$elseuser->id)->orderBy('created_at', 'desc')->paginate(10);
            return view('users.org.profile.researcherprofile')->with('profile', $profile)->with('vulns',$vulns)->with('elseprofile',$elseprofile)->with('elseuser',$elseuser);
          }
        }
      }
      elseif(Auth::user()->role_id == 3) { //researcher
        if (Auth::user()->username == $uname) { //user viewing own profile
          $loggedin = Auth::user();
          $profile = Utility::getProfile($loggedin);
          $vulns = Vulnerability::with('product')->where('user_vul_author_id','=',$loggedin->id)->orderBy('created_at', 'desc')->paginate(10);

          return view('users.researcher.profile.ownprofile')->with('profile', $profile)->with('vulns',$vulns);
        }
        else { //user viewing someone else's profile
          $loggedin = Auth::user();
          $profile = Utility::getProfile($loggedin);

          $elseuser = User::where('username', $uname)->first();
          $elseprofile = Utility::getProfile($elseuser);

          if($elseuser->role_id == 2) { //researcher views org
            $vulns = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')->where('user_p_id','=',$elseuser->id)->orderBy('vulnerabilities.created_at', 'desc')->paginate(10);
            return view('users.researcher.profile.vendorprofile')->with('profile', $profile)->with('vulns',$vulns)->with('elseprofile',$elseprofile)->with('elseuser',$elseuser);
          }
          elseif($elseuser->role_id == 3) { //researcher views researcher
            $vulns = Vulnerability::with('product')->where('user_vul_author_id','=',$elseuser->id)->orderBy('created_at', 'desc')->paginate(10);
            return view('users.researcher.profile.viewresearcherprofile')->with('profile', $profile)->with('vulns',$vulns)->with('elseprofile',$elseprofile)->with('elseuser',$elseuser);
          }
        }
      }
    }

    /**
     * GET View to display form for editing profile information
     *
     * @return view
     */
    protected function editform() {
      $userid = Auth::user()->id;
      if (Auth::user()->role_id==1) {
        return view('users.admin.profile.edit')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==2) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.org.profile.edit')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==3) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.researcher.profile.edit')->with('profile', $profile);
      }
    }

    /**
     * POST Ajax upload profile picture
     *
     * @param Request $request
     *
     * @return Response
     */
    protected function profpicupload(Request $request) {
      if($request->ajax()) {
        $file = $request->image;
        $input = array('image' => $file);
        $rules = array(
          'image' => 'mimes:jpeg,jpg,png|required|max:10000'
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails() )
        {
          return Response::json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
        }
        else {
          $img = Image::make($file)->resize(200, 250);
          $pathcheck = public_path().'/user/uploads/'.Auth::user()->username.'/';
          if(!File::exists($pathcheck)) {
            File::makeDirectory($pathcheck, 0775, true); //public access
          }
          $newfilename = str_random(6).$file->getClientOriginalName();
          $img->save($pathcheck.$newfilename);
          $profile = Utility::getProfile(Auth::user());
          //$userid = Auth::user()->id;
          //$profile = User::with('profile')->find($userid)->profile;
          $profile->profpic = $newfilename;
          $profile->save();
          return Response::json(['success' => true, 'file' => $newfilename]);
        }
      }
      else {
          return response('Invalid request.', 400);
      }
    }

    /**
     * POST Ajax save profile information
     *
     * @param Request $request
     *
     * @return Response
     */
    protected function saveProfileinfo(Request $request) {
      if($request->ajax()) {
        Utility::killXSS();
        $userid = Auth::user()->id;
        $roleid = Auth::user()->role_id;
        if($roleid == 2) { //save org profile
          $rules = array(
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'display_name' => 'required|max:255',
            'legal_name' => 'required|max:255',
            'address' => 'string',
            'bio' => 'string',
            'pincode' => 'integer',
            'mobilenumber'=>'integer',
            'gender' => 'required|integer',
            'designation' => 'required|string'
          );
          $profile = User::with('orgprofile')->find($userid)->orgprofile;
          $validator = Validator::make($request->all(), $rules);
          if($validator->passes()) {
            $profile->first_name = ucfirst($request->first_name);
            $profile->last_name = ucfirst($request->last_name);
            $profile->legal_name = ucfirst($request->legal_name);
            $profile->display_name = ucfirst($request->display_name);
            $profile->address = $request->address;
            $profile->gender = $request->gender;
            $profile->bio = $request->bio;
            $profile->mobilenumber = $request->mobilenumber;
            $profile->pincode = $request->pincode;
            $profile->designation = $request->designation;
            $profile->save();
            $response = array(
                'status' => 'success',
                'msg' => 'Saved',
            );
            return Response::json($response);
          }
          else {
            $response = array(
                'status' => 'error',
                'msg' => $validator->getMessageBag()->toArray(),
            );
            return Response::json($response);
          }
        }
        elseif($roleid == 3) { //save res profile
          $rules = array(
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'address' => 'string',
            'bio' => 'string',
            'pincode' => 'integer',
            'mobilenumber'=>'integer',
            'gender' => 'required|integer',
            'designation' => 'required|string'
          );
          $profile = User::with('resprofile')->find($userid)->resprofile;
          $validator = Validator::make($request->all(), $rules);
          if($validator->passes()) {
            $profile->first_name = ucfirst($request->first_name);
            $profile->last_name = ucfirst($request->last_name);
            $profile->address = $request->address;
            $profile->gender = $request->gender;
            $profile->bio = $request->bio;
            $profile->mobilenumber = $request->mobilenumber;
            $profile->pincode = $request->pincode;
            $profile->designation = $request->designation;
            $profile->save();
            $response = array(
                'status' => 'success',
                'msg' => 'Saved',
            );
            return Response::json($response);
          }
          else {
            $response = array(
                'status' => 'error',
                'msg' => $validator->getMessageBag()->toArray(),
            );
            return Response::json($response);
          }
        }
      }
      else {
          return response('Invalid request.', 400);
      }
    }

    /**
     * GET Get notifications related to the user
     *
     * @return Response
     */
    protected function showNotifications() {
      $userid = Auth::user()->id;
      if (Auth::user()->role_id==1) {
        return view('users.admin.shownotifications')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==2) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.org.shownotifications')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==3) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.researcher.shownotifications')->with('profile', $profile);
      }
    }

    /**
     * GET Mark authenticated user's notifications as read
     *
     * @return redirect
     */
    protected function markNotifications() {
      Notifynder::readAll(Auth::user()->id);
      return redirect()->back()->with('message','Marked all read!');
    }
}
