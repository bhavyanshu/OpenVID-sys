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

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

use openvidsys\User;
use openvidsys\Model\OrgProfile;
use openvidsys\Model\ResProfile;
use openvidsys\Model\Product;
use openvidsys\Model\Vulnerability;
use openvidsys\Http\Requests;
use openvidsys\Common\Utility;

/**
 * Controller for dashboard related features
 */
class HomeController extends Controller
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
     * GET View for dashboard based on role
     *
     * @return Response
     */
    public function index()
    {
      $userid = Auth::user()->id;
      if (Auth::user()->role_id==1) { //admin
        $profile = Utility::getProfile(Auth::user());
        return view('users.admin.dashboard')
        ->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==2) { //org/vendor
        $loggedin = Auth::user();
        $profile = Utility::getProfile($loggedin);

        $vulns = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')->where('user_p_id','=',$loggedin->id)->orderBy('vulnerabilities.created_at', 'desc')->take(10)->get();
        $activevulns = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')->where('user_p_id','=',$loggedin->id)->where('vul_status','=',1)->orderBy('vulnerabilities.created_at', 'desc')->take(10)->get();
        $highthreatvulns = Vulnerability::leftJoin('products', 'vulnerabilities.vul_prod_id', '=', 'products.p_id')->where('user_p_id','=',$loggedin->id)->where('threat_level','>=','7')->orderBy('vulnerabilities.created_at', 'desc')->take(10)->get();
        $products = Product::where('user_p_id','=',$loggedin->id)->get();

        return view('users.org.dashboard')
        ->with('profile', $profile)->with('products',$products)->with('vulns',$vulns)->with('activevulns',$activevulns)->with('highthreatvulns',$highthreatvulns);
      }
      elseif (Auth::user()->role_id==3) { //researcher
        $loggedin = Auth::user();
        $profile = Utility::getProfile($loggedin);

        $vulns = Vulnerability::with('product')->where('user_vul_author_id','=',$loggedin->id)->orderBy('created_at', 'desc')->take(10)->get();
        $activevulns = Vulnerability::with('product')->where('user_vul_author_id','=',$loggedin->id)->where('vul_status','=','1')->orderBy('created_at', 'desc')->take(10)->get();
        $highthreatvulns = Vulnerability::with('product')->where('vul_status','=','1')->where('threat_level','>=','7')->orderBy('created_at', 'desc')->take(10)->get();

        return view('users.researcher.dashboard')
        ->with('profile',$profile)->with('vulns',$vulns)->with('activevulns',$activevulns)->with('highthreatvulns',$highthreatvulns);
      }
    }
}
