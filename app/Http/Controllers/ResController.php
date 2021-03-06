<?php
/**
 * OpenVID-sys
 *
 * @copyright 2016 Bhavyanshu Parasher (https://bhavyanshu.me)
 * @license   http://www.apache.org/licenses/LICENSE-2.0.txt Apache License
 * @link      https://bhavyanshu.me/pages/openvid_sys/
 */
namespace openvidsys\Http\Controllers;

use DB;
use Carbon\Carbon;
use Validator;
use Hash;
use File;
use Storage;
use Notifynder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use openvidsys\Common\Utility;
use openvidsys\User;
use openvidsys\Model\ResProfile;
use openvidsys\Model\Product;
use openvidsys\Model\Vulnerability;
use openvidsys\Http\Controllers\Controller;
use openvidsys\Http\Requests;

/**
 * Controller for Researcher role specific methods
 */
class ResController extends Controller
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
     * GET View to display form for reporting vulnerability.
     *
     * @param int $productid
     *
     * @return Response
     */
    protected function createVulnreport($productid) {
      $profile = Utility::getProfile(Auth::user());
      $product = Product::where('p_id','=',$productid)->firstOrFail();
      return View('users.researcher.editvulnreport')->with('product',$product)->with('profile', $profile)->with('vul',null);
    }

    /**
     * GET View to display form for updating vulnerability report.
     *
     * @param int $vid
     *
     * @return Response
     */
    protected function editVulnreport($vid) {
      $profile = Utility::getProfile(Auth::user());
      $vul = Vulnerability::where('vul_id','=',$vid)->firstOrFail();
      return View('users.researcher.editvulnreport')->with('vul',$vul)->with('profile', $profile)->with('product',null);
    }

    /**
     * POST Save information regarding the vulnerability report
     *
     * @param Request $request
     *
     * @return Response
     */
    protected function saveVulnreport(Request $request)
    {
      Utility::killXSS();
      $sendArr = array();
      $sendArr['vul_type'] = $request->vul_type;
      $sendArr['vul_complexity'] = $request->vul_complexity;
      $sendArr['vul_auth'] = $request->vul_auth;
      $sendArr['vul_confidentiality'] = $request->vul_confidentiality;
      $sendArr['vul_integrity'] = $request->vul_integrity;
      $sendArr['vul_performance'] = $request->vul_performance;
      $sendArr['vul_access'] = $request->vul_access;
      $finalscore = Utility::computeScore($sendArr);
      if(!is_null($request->vul_prod_id)) { //create new vulnerability against product
        $rules = array(
          'vul_prod_id' => 'required|integer',
          'vul_author_name' => 'required|max:255',
          'vul_author_email' => 'required|min:3|email',
          'vul_type' => 'required',
          'vul_complexity' => 'required',
          'vul_auth' => 'required',
          'vul_confidentiality' => 'required',
          'vul_integrity' => 'required',
          'vul_performance' => 'required',
          'vul_access' => 'required',
          'vul_description' => 'required|string',
          'ref_url_1' => 'url',
          'ref_url_2' => 'url',
          'ref_url_3' => 'url',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes())
        {
          $v = new Vulnerability;
          $v->user()->associate(Auth::user());
          $v->vul_author_name = ucfirst($request->vul_author_name);
          $v->vul_author_email = $request->vul_author_email;
          $v->vul_unique_id = implode("-",str_split(strtoupper(uniqid("VUL")),7));
          $v->vul_type = $request->vul_type;
          $v->vul_auth = $request->vul_auth;
          $v->vul_prod_id = $request->vul_prod_id;
          $v->vul_complexity = $request->vul_complexity;
          $v->vul_confidentiality = $request->vul_confidentiality;
          $v->vul_integrity = $request->vul_integrity;
          $v->vul_performance = $request->vul_performance;
          $v->vul_access = $request->vul_access;
          $v->vul_description = $request->vul_description;
          $v->ref_url_1 = $request->ref_url_1;
          $v->ref_url_2 = $request->ref_url_2;
          $v->ref_url_3 = $request->ref_url_3;
          $v->threat_level = $finalscore;
          $v->vul_status = 1;
          $v->save();

          $vulnid = $v->vul_id;
          $from_user_id = Auth::user()->id;
          $getprod = Product::where('p_id','=',$request->vul_prod_id)->firstOrFail();

          $to_user_id = $getprod->user_p_id;
          $to_userinfo = User::with('orgprofile')->find($to_user_id);
          $to_user_email = $to_userinfo->email;
          $to_user_firstname = $to_userinfo->orgprofile->first_name;
          $to_user_lastname = $to_userinfo->orgprofile->last_name;

          Notifynder::category('user.postedflaw')
              ->from($from_user_id)
              ->to($to_user_id)
              ->url('/vulnerability/'.$vulnid)
              ->send();

          $vulnid = array('vulnid' => $vulnid,
                          'to_user_email' => $to_user_email,
                          'to_user_firstname' => $to_user_firstname,
                          'to_user_lastname' => $to_user_lastname);
          \Mail::send('emails.vulns.registered', $vulnid, function($message) use ($vulnid) {
              $message->to($vulnid['to_user_email'], ucfirst($vulnid['to_user_firstname']).' '.ucfirst($vulnid['to_user_lastname']))
                  ->subject('New Vulnerability Reported against your product');
          });

          return redirect()->route('dashboard')->with('message','Your new vulnerability has been registered.');
        }
        else {
          return redirect()->back()->withInput()
            ->withErrors($validator);
        }
      }
      elseif(!is_null($request->vul_id)) {
        $rules = array(
          'vul_id' => 'required',
          'vul_unique_id' => 'required',
          'vul_author_name' => 'required|max:255',
          'vul_author_email' => 'required|min:3|email',
          'vul_type' => 'required',
          'vul_complexity' => 'required',
          'vul_auth' => 'required',
          'vul_confidentiality' => 'required',
          'vul_integrity' => 'required',
          'vul_performance' => 'required',
          'vul_access' => 'required',
          'vul_description' => 'required|string',
          'ref_url_1' => 'url',
          'ref_url_2' => 'url',
          'ref_url_3' => 'url',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes())
        {
          $v = Vulnerability::where('user_vul_author_id','=',Auth::user()->id)->where('vul_id','=',$request->vul_id)->where('vul_unique_id','=',$request->vul_unique_id)->firstOrFail();
          $v->vul_author_name = ucfirst($request->vul_author_name);
          $v->vul_author_email = $request->vul_author_email;
          $v->vul_type = $request->vul_type;
          $v->vul_auth = $request->vul_auth;
          $v->vul_complexity = $request->vul_complexity;
          $v->vul_confidentiality = $request->vul_confidentiality;
          $v->vul_integrity = $request->vul_integrity;
          $v->vul_performance = $request->vul_performance;
          $v->vul_access = $request->vul_access;
          $v->vul_description = $request->vul_description;
          $v->ref_url_1 = $request->ref_url_1;
          $v->ref_url_2 = $request->ref_url_2;
          $v->ref_url_3 = $request->ref_url_3;
          $v->threat_level = $finalscore;
          $v->vul_status = 1;
          $v->save();
          return redirect()->route('dashboard')->with('message','The information has been updated.');
        }
        else {
          return redirect()->back()->withInput()
            ->withErrors($validator);
        }
      }
    }
}
