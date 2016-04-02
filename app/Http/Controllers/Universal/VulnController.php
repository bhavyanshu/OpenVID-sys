<?php
/**
 * OpenVID-sys
 *
 * @copyright 2016 Bhavyanshu Parasher (https://bhavyanshu.me)
 * @license   http://www.apache.org/licenses/LICENSE-2.0.txt Apache License
 * @link      https://bhavyanshu.me/pages/openvid_sys/
 */
namespace openvidsys\Http\Controllers\Universal;

use DB;
use Hash;
use Validator;
use Response;
use Datatables;
use File;
use Image;
use Notifynder;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;

use openvidsys\User;
use openvidsys\Model\ResProfile;
use openvidsys\Model\OrgProfile;
use openvidsys\Model\Product;
use openvidsys\Model\Vulnerability;
use openvidsys\Common\Utility;
use openvidsys\Http\Controllers\Controller;

/**
 * Controller for vulnerability manager
 */
class VulnController extends Controller
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
     * GET Display vulnerability report based on logged in user role.
     *
     * @param int $vid
     *
     * @return view
     */
    protected function getVulninfo($vid) {
      $vuln = Vulnerability::where('vul_id','=',$vid)->firstOrFail();
      $product = Product::with('user')->where('p_id','=',$vuln->vul_prod_id)->firstOrFail();
      $vendorprof = Utility::getProfile($product->user);

      if (Auth::user()->role_id==2) {
        $profile = Utility::getProfile(Auth::user());
        return View('users.org.showvulninfo')->with('vuln',$vuln)->with('profile', $profile)->with('product',$product)->with('vendorprof',$vendorprof);
      }
      elseif (Auth::user()->role_id==3) {
        $profile = Utility::getProfile(Auth::user());
        return View('users.researcher.showvulninfo')->with('vuln',$vuln)->with('profile', $profile)->with('product',$product)->with('vendorprof',$vendorprof);
      }
    }

    /**
     * GET View displaying form to search for vulnerabilities.
     *
     * @return Response
     */
    protected function getSearchform() {
      $userid = Auth::user()->id;
      if (Auth::user()->role_id==1) {
        return view('users.admin.searchvulnform')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==2) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.org.searchvulnform')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==3) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.researcher.searchvulnform')->with('profile', $profile);
      }
    }

    /**
     * POST Populate datatables based on search query relating to vulnerabilities.
     *
     * @param Request $request
     *
     * @return Datatables
     */
    protected function getJVulnslist(Request $request) {
      if(strlen(trim($request->search_p_name)) > 1 && strlen(trim($request->search_p_vendor_name)) < 1) {
        $getvuls = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')
        ->where('products.p_name', 'LIKE','%'.$request->search_p_name.'%')
        ->where('p_status','=','1')
        ->select('products.p_id as pid','vulnerabilities.vul_unique_id','vulnerabilities.vul_id','products.p_name','products.p_author_name','vulnerabilities.created_at');
      }
      elseif(strlen(trim($request->search_p_name)) > 1 && strlen(trim($request->search_p_vendor_name)) > 1) {
        $getvuls = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')
        ->where('products.p_name', 'LIKE', '%'.$request->search_p_name.'%')
        ->where('products.p_author_name','LIKE','%'.$request->search_p_vendor_name.'%')
        ->where('p_status','=','1')
        ->select('products.p_id as pid','vulnerabilities.vul_unique_id','vulnerabilities.vul_id','products.p_name','products.p_author_name','vulnerabilities.created_at');
      }
      elseif(strlen(trim($request->search_vidsys_id)) > 1) {
        $getvuls = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')
        ->where('vulnerabilities.vul_unique_id', 'LIKE','%'.$request->search_vidsys_id.'%')
        ->where('p_status','=','1')
        ->select('products.p_id as pid','vulnerabilities.vul_unique_id','vulnerabilities.vul_id','products.p_name','products.p_author_name','vulnerabilities.created_at');
      }
      return Datatables::of($getvuls)
      ->edit_column('vul_unique_id','<a href="/vulnerability/{{$vul_id}}">{{$vul_unique_id}}</a>')
      ->edit_column('p_name','<a href="/product/{{$pid}}">{{$p_name}}</a>')
      ->make(true);
    }

    /**
     * GET Display form to search filter vulnerabilities based on status.
     *
     * @return Response
     */
    protected function vulnTracker() {
      $userid = Auth::user()->id;
      if (Auth::user()->role_id==1) {
        return view('users.admin.vulntracker')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==2) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.org.vulntracker')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==3) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.researcher.vulntracker')->with('profile', $profile);
      }
    }

    /**
     * POST Populate datatable based on filter type
     *
     * @param String $type
     *
     * @return datatable obj
     */
    protected function listVulbytype($type) {
      $loggedinid = Auth::user()->id;
      if(trim($type) == "all") //all
      {
        if(Auth::user()->role_id==2) { //products by logged in vendor only
          $getvuls = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')
          ->where('products.user_p_id','=',$loggedinid)
          ->select('products.p_id as pid','vulnerabilities.vul_unique_id','vulnerabilities.vul_id','products.p_name','products.p_author_name','vulnerabilities.created_at');
        }
        elseif(Auth::user()->role_id==3) { //vuls by logged in researcher only
          $getvuls = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')
          ->where('vulnerabilities.user_vul_author_id','=',$loggedinid)
          ->select('products.p_id as pid','vulnerabilities.vul_unique_id','vulnerabilities.vul_id','products.p_name','products.p_author_name','vulnerabilities.created_at');
        }
      }
      elseif(trim($type) == "ac") //active
      {
        if(Auth::user()->role_id==2) { //products by logged in vendor only
          $getvuls = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')
          ->where('p_status','=','1')
          ->where('products.user_p_id','=',$loggedinid)
          ->select('products.p_id as pid','vulnerabilities.vul_unique_id','vulnerabilities.vul_id','products.p_name','products.p_author_name','vulnerabilities.created_at');
        }
        elseif(Auth::user()->role_id==3) { //vuls by logged in researcher only
          $getvuls = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=','products.p_id')
          ->where('p_status','=','1')
          ->where('vulnerabilities.user_vul_author_id','=',$loggedinid)
          ->select('products.p_id as pid','vulnerabilities.vul_unique_id','vulnerabilities.vul_id','products.p_name','products.p_author_name','vulnerabilities.created_at');
        }
      }
      elseif(trim($type) == "ht") //high threat
      {
        if(Auth::user()->role_id==2) { //products by logged in vendor only
          $getvuls = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')
          ->where('vulnerabilities.threat_level', '>=','7')
          ->where('p_status','=','1')
          ->where('products.user_p_id','=',$loggedinid)
          ->select('products.p_id as pid','vulnerabilities.vul_unique_id','vulnerabilities.vul_id','products.p_name','products.p_author_name','vulnerabilities.created_at');
        }
        elseif(Auth::user()->role_id==3) { //vuls by logged in researcher only
          $getvuls = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')
          ->where('vulnerabilities.threat_level', '>=', '7')
          ->where('p_status','=','1')
          ->where('vulnerabilities.user_vul_author_id','=',$loggedinid)
          ->select('products.p_id as pid','vulnerabilities.vul_unique_id','vulnerabilities.vul_id','products.p_name','products.p_author_name','vulnerabilities.created_at');
        }
      }
      return Datatables::of($getvuls)
      ->edit_column('vul_unique_id',
      ' @if(Auth::user()->role_id==2)
          <a href="/vulnerability/{{$vul_id}}">{{$vul_unique_id}}</a>
        @elseif(Auth::user()->role_id==3)
          <a href="/vuln/edit/{{$vul_id}}">{{$vul_unique_id}}</a>
        @endif')
      ->edit_column('p_name',
      ' @if(Auth::user()->role_id==2)
          <a href="/product/edit/{{$pid}}">{{$p_name}}</a>
        @elseif(Auth::user()->role_id==3)
          <a href="/product/{{$pid}}">{{$p_name}}</a>
        @endif')
      ->make(true);
    }

    /**
     * GET View displaying form to add patch related information and
     * update status.
     *
     * @param int $vid
     *
     * @return Response
     */
    protected function getMarkresolvedform($vid) {
      $loggedin = Auth::user();
      $vuln = Vulnerability::where('vul_id','=',$vid)->firstOrFail();
      $product = Product::with('user')->where('user_p_id','=',$loggedin->id)->where('p_id','=',$vuln->vul_prod_id)->firstOrFail(); //make sure the product belongs to person accessing this controller
      $profile = Utility::getProfile(Auth::user());
      return View('users.org.changevulnstatusform')->with('vuln',$vuln)->with('profile', $profile)->with('product',$product);
    }

    /**
     * POST Update vulnerability status & notify users
     *
     * @param Request $request
     *
     * @return Response
     */
    protected function postMarkresolved(Request $request) {
      Utility::killXSS();
      $rules = array(
        'vul_id' => 'required',
        'vul_unique_id' => 'required',
        'user_vul_author_id' => 'required',
        'patch_description' => 'required|string',
        'patch_url' => 'url',
        'vul_status' => 'required'
      );
      $validator = Validator::make($request->all(), $rules);
      if ($validator->passes())
      {
        $v = Vulnerability::where('user_vul_author_id','=',$request->user_vul_author_id)->where('vul_id','=',$request->vul_id)->where('vul_unique_id','=',$request->vul_unique_id)->firstOrFail();
        $productcheck = Product::where('user_p_id','=',Auth::user()->id)->firstOrFail();

        //Now check whether prod ids are same -> means product belongs to logged in vendor
        if($v->vul_prod_id == $productcheck->p_id) {
          $cachestatus = $request->vul_status;
          if($cachestatus == 0) { //was open so mark as fixed
            $v->patch_description = $request->patch_description;
            $v->patch_url = $request->patch_url;
            $v->vul_status = $cachestatus;
          }
          elseif($cachestatus == 1) { //rollback patch info and open this issue again
            $v->patch_description = null;
            $v->patch_url = null;
            $v->vul_status = $cachestatus;
          }
          elseif($cachestatus == 2) { //mark as won't fix but keep patch info
            $v->patch_description = $request->patch_description;
            $v->patch_url = $request->patch_url;
            $v->vul_status = $cachestatus;
          }

          $v->save();
          $from_user_id = Auth::user()->id;
          $to_user_id = $v->user_vul_author_id;

          $to_userinfo = User::with('resprofile')->find($to_user_id);
          $to_user_email = $to_userinfo->email;
          $to_user_firstname = $to_userinfo->resprofile->first_name;
          $to_user_lastname = $to_userinfo->resprofile->last_name;

          Notifynder::category('vendor.markedfixed')
              ->from($from_user_id)
              ->to($to_user_id)
              ->url('/vulnerability/'.$v->vul_id)
              ->send();

          $vulninfo = array(
                          'vulnid' => $v->vul_id,
                          'vul_unique_id' => $request->vul_unique_id,
                          'to_user_email' => $to_user_email,
                          'to_user_firstname' => $to_user_firstname,
                          'to_user_lastname' => $to_user_lastname);
          \Mail::send('emails.vulns.statusupdated', $vulninfo, function($message) use ($vulninfo) {
              $message->to($vulninfo['to_user_email'], ucfirst($vulninfo['to_user_firstname']).' '.ucfirst($vulninfo['to_user_lastname']))
                  ->subject($vulninfo['vul_unique_id'].' Vulnerability status has been updated');
          });

          return redirect('/vulnerability/'.$v->vul_id)->with('message','The information has been updated.');
        }
        else {
          return redirect()->back()->with('message','The provided information was incorrect.');
        }
      }
      else {
        return redirect()->back()->withInput()
          ->withErrors($validator);
      }
    }
}
