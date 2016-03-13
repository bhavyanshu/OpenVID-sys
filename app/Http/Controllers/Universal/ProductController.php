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
 * Controller for product related methods
 */
class ProductController extends Controller
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
     * GET Returns view to display product search form.
     *
     * @return Response
     */
    protected function getSearchform() {
      $userid = Auth::user()->id;
      if (Auth::user()->role_id==1) {
        return view('users.admin.searchproductform')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==2) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.org.searchproductform')->with('profile', $profile);
      }
      elseif (Auth::user()->role_id==3) {
        $profile = Utility::getProfile(Auth::user());
        return view('users.researcher.searchproductform')->with('profile', $profile);
      }
    }

    /**
     * GET Returns selective information of products for Datatables.
     *
     * @param Request $request
     *
     * @return Datatables
     */
    protected function getJProductslist(Request $request) {
      if(strlen(trim($request->search_p_name)) > 1 && strlen(trim($request->search_p_vendor)) < 1) {
        $getproducts = Product::with('vulnerability')
        ->where('p_name','LIKE','%'.$request->search_p_name.'%')
        ->where('p_status','=','1')->select('products.p_id as pid','products.p_name','products.p_author_name','products.created_at');
      }
      elseif(strlen(trim($request->search_p_name)) < 1 && strlen(trim($request->search_p_vendor)) > 1) {
        $getproducts = Product::with('vulnerability')
        ->where('p_author_name', 'LIKE','%'.$request->search_p_vendor.'%')
        ->where('p_status','=','1')
        ->select('products.p_id as pid', 'products.p_name','products.p_author_name','products.created_at');
      }
      elseif(strlen(trim($request->search_p_name)) > 1 && strlen(trim($request->search_p_vendor)) > 1) {
        $getproducts = Product::with('vulnerability')
        ->where('p_name', 'LIKE', '%'.$request->search_p_name.'%')
        ->where('p_author_name','LIKE','%'.$request->search_p_vendor.'%')
        ->where('p_status','=','1')->select('products.p_id as pid','products.p_name','products.p_author_name','products.created_at');
      }
      $dtbs = Datatables::of($getproducts)
      ->edit_column('p_name',
      '<a href="/product/{{$pid}}">{{$p_name}}</a>')
      ->edit_column('p_actions',
      '<a class="btn btn-sm btn-info" href="/product/{{$pid}}">View</a>
       @if(Auth::user()->role_id==2)
        <a class="btn btn-sm btn-danger" href="/product/edit/{{$pid}}">Edit</a>
       @elseif(Auth::user()->role_id==3)
        <a class="btn btn-sm btn-danger" href="/vuln/create/{{$pid}}">Report</a>
       @endif')
      ->make(true);
      return $dtbs;
    }

    /**
     * GET View for displaying information for single product by
     * productid
     *
     * @param int $productid
     *
     * @return Response
     */
    protected function getProductinfo($productid) {
      $product = Product::where('p_id','=',$productid)->firstOrFail();
      $vulns = Vulnerability::where('vul_prod_id','=',$product->p_id)->get();

      $vulmeta = Utility::productGraphs($vulns);

      if (Auth::user()->role_id==2) {
        $profile = Utility::getProfile(Auth::user());
        return View('users.org.showproductinfo')->with('vulns',$vulns)->with('profile', $profile)->with('product',$product)->with('threat_values',$vulmeta['getthreatvalues'])->with('vultypes',$vulmeta['getvultype']);
      }
      elseif (Auth::user()->role_id==3) {
        $profile = Utility::getProfile(Auth::user());
        return View('users.researcher.showproductinfo')->with('vulns',$vulns)->with('profile', $profile)->with('product',$product)->with('threat_values',$vulmeta['getthreatvalues'])->with('vultypes',$vulmeta['getvultype']);
      }
    }
}
