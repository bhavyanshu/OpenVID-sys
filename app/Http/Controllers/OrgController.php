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

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use openvidsys\User;
use openvidsys\Http\Requests;
use openvidsys\Common\Utility;
use openvidsys\Model\OrgProfile;
use openvidsys\Model\Product;
use openvidsys\Http\Controllers\Controller;

/**
 * Controller for org/vendor specific methods
 */
class OrgController extends Controller
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
   * GET View to display form to register product.
   *
   * @return Response
   */
  public function createProduct() {
    $profile = Utility::getProfile(Auth::user());
    return View('users.org.editproduct')->with('product',null)->with('profile', $profile);
  }

  /**
   * GET View to display form for editing registered product
   *
   * @param int $productid
   *
   * @return Response
   */
  public function editProduct($productid) {
    $profile = Utility::getProfile(Auth::user());
    $product = Product::where('user_p_id','=',Auth::user()->id)->where('p_id','=',$productid)->firstOrFail();
    return View('users.org.editproduct')->with('product',$product)->with('profile', $profile);
  }

  /**
   * POST Save project related information
   *
   * @param Request $request
   *
   * @return Response
   */
  public function saveProduct(Request $request) {
    Utility::killXSS();
    if(is_null($request->p_id)) { //create new product
      $rules = array(
        'p_name' => 'required|max:255',
        'p_author_name' => 'required|max:255',
        'p_author_email' => 'required|min:6|email',
        'p_description' => 'required',
        'p_url' => 'required',
        'p_type' => 'required',
      );
      $validator = Validator::make($request->all(), $rules);
      if ($validator->passes())
      {
        $p = new Product;
        $p->user()->associate(Auth::user());
        $p->p_name = ucfirst($request->p_name);
        $p->p_author_name = ucfirst($request->p_author_name);
        $p->p_author_email = $request->p_author_email;
        $p->p_description = $request->p_description;
        $p->p_url = $request->p_url;
        $p->p_type = $request->p_type;
        $p->save();
        return redirect()->route('dashboard')->with('message','A new product has been added.');
      }
      else {
        return redirect()->back()->withInput()
          ->withErrors($validator);
      }
    }
    else { //edit product
      $rules = array(
        'p_id' => 'required',
        'p_name' => 'required|max:255',
        'p_author_name' => 'required|max:255',
        'p_author_email' => 'required|min:6|email',
        'p_description' => 'required',
        'p_url' => 'required',
        'p_type' => 'required',
      );
      $validator = Validator::make($request->all(), $rules);
      if ($validator->passes())
      {
        $p = Product::where('user_p_id','=',Auth::user()->id)->where('p_id','=',$request->p_id)->firstOrFail();
        $p->user()->associate(Auth::user());
        $p->p_name = ucfirst($request->p_name);
        $p->p_author_name = ucfirst($request->p_author_name);
        $p->p_author_email = $request->p_author_email;
        $p->p_description = $request->p_description;
        $p->p_url = $request->p_url;
        $p->p_type = $request->p_type;
        $p->save();
        return redirect()->route('dashboard')->with('message','A new product has been added.');
      }
      else {
        return redirect()->back()->withInput()
          ->withErrors($validator);
      }
    }
  }
}
