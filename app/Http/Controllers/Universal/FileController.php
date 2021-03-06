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
use Storage;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;

use openvidsys\User;
use openvidsys\Model\Attachment;
use openvidsys\Http\Controllers\Controller;

/**
 * Controller for file manager related methods
 */
class FileController extends Controller
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
   * GET To download file after matching filetoken
   *
   * @param  string $filetoken
   *
   * @return Response
   */
  public function getFile($filetoken){
    $entry = Attachment::where('file_token', '=', $filetoken)->firstOrFail();
    $storagePath = storage_path().'/app/userfiles/uploads/'.$entry->user->username.'/'.$entry->file_name;
    return response()->download($storagePath);
  }
}
