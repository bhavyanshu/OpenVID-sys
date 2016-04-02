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
use Editor;
use Notifynder;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use openvidsys\User;
use openvidsys\Model\ResProfile;
use openvidsys\Model\OrgProfile;
use openvidsys\Model\Product;
use openvidsys\Model\Comment;
use openvidsys\Model\Attachment;
use openvidsys\Model\Vulnerability;
use openvidsys\Common\Utility;
use openvidsys\Http\Controllers\Controller;

/**
 * Controller for comment related methods
 */
class CommentController extends Controller
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
     * POST Ajax based add comment method.
     * The output should be in json format.
     *
     * @param  Request $request
     *
     * @return Response;
     */
    protected function addComment(Request $request) {
      Utility::killXSS();
      $content = Editor::content(Input::get(Editor::input()));
      $rules = array(
        'com_vul_id' => 'required',
        'vinelab-editor-text' => 'required|max:10000',
      );
      $messages = array(
        'vinelab-editor-text.max' => 'Comment cannot be greater than 10000 characters.',
        'vinelab-editor-text.required' => 'You should really write something in here.'
      );
      $validator = Validator::make($request->all(), $rules, $messages);
      if($validator->passes()) {

        $comment = new Comment;
        $comment->user()->associate(Auth::user());
        $comment->com_vul_id = $request->com_vul_id;
        $comment->com_text = $content->toJson()->html;
        $comment->save();
        $comment_id = $comment->com_id;
        $comment_vul_id = $comment->com_vul_id;

        if(Auth::user()->role_id == 2) { //vendor
          $from_user_id = Auth::user()->id;
          $getvuln = Vulnerability::where('vul_id','=',$request->com_vul_id)->firstOrFail();
          $to_user_id = $getvuln->user_vul_author_id;

          Notifynder::category('user.postedcomment')
              ->from($from_user_id)
              ->to($to_user_id)
              ->url('/vulnerability/'.$comment_vul_id.'#comment-'.$comment_id)
              ->send();

          if(!is_null($request->replyto_id) && strlen(trim($request->replyto_id))>0 && $to_user_id != $request->replyto_id) {
            $replyto_user_id = $request->replyto_id;
            if(Comment::where('user_com_id', '=', $replyto_user_id)->where('com_vul_id','=',$request->com_vul_id)->exists()) {
              //Just to confirm if user has indeed interacted with the post
              Notifynder::category('user.postedcomment')
                  ->from($from_user_id)
                  ->to($replyto_user_id)
                  ->url('/vulnerability/'.$request->com_vul_id.'#comment-'.$comment_id)
                  ->send();
            }
          }
        }
        elseif(Auth::user()->role_id == 3) { //res
          $from_user_id = Auth::user()->id;
          $getprod = Vulnerability::with('product')->where('vul_id','=',$request->com_vul_id)->firstOrFail();
          $to_user_id = $getprod->product->user_p_id;

          if(!is_null($request->replyto_id) && strlen(trim($request->replyto_id))>0 && $to_user_id != $request->replyto_id) {
            $replyto_user_id = $request->replyto_id;
            if(Comment::where('user_com_id', '=', $replyto_user_id)->where('com_vul_id','=',$request->com_vul_id)->exists()) {
              //Just to confirm if user has indeed interacted with the post
              Notifynder::category('user.postedcomment')
                  ->from($from_user_id)
                  ->to($replyto_user_id)
                  ->url('/vulnerability/'.$request->com_vul_id.'#comment-'.$comment_id)
                  ->send();
            }
          }
        }

        //bulk email to all users who have interacted on this vulnerability report
        $vulninfo = array (
                      'vulnid' => $comment_vul_id,
                      'comment_id' => $comment_id
                    );
        $this->mailtoUsersWithComm($vulninfo);

        $response = array(
            'status' => 'success',
            'msg' => 'Comment Posted!',
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

    /**
     * GET Returns view of file attachment form
     * for adding comments. Requires vulneraibility ID.
     *
     * @param  int $vid
     */
    protected function getCommentfileform($vid) {
      $vuln = Vulnerability::where('vul_id','=',$vid)->firstOrFail();
      $product = Product::with('user')->where('p_id','=',$vuln->vul_prod_id)->firstOrFail();
      $vendorprof = Utility::getProfile($product->user);

      if (Auth::user()->role_id==2) {
        $profile = Utility::getProfile(Auth::user());
        return View('users.org.showcommentfileform')->with('vuln',$vuln)->with('profile', $profile)->with('product',$product)->with('vendorprof',$vendorprof)->with('content',null);
      }
      elseif (Auth::user()->role_id==3) {
        $profile = Utility::getProfile(Auth::user());
        return View('users.researcher.showcommentfileform')->with('vuln',$vuln)->with('profile', $profile)->with('product',$product)->with('vendorprof',$vendorprof)->with('content',null);
      }
    }

    /**
     * POST File attachment based add comment method.
     * No json response needed here.
     *
     * @param  Request $request
     *
     * @return redirect
     */
    protected function addCommentwfile(Request $request) {
      Utility::killXSS();

      $content = Editor::content(Input::get(Editor::input()));
      $contenthtml = $content->toJson()->html; //save in DB
      $contentmarkdown = Input::get(Editor::input()); //to return to view, if there is any error
      //dd($content->markdown());
      $rules = array(
        'com_vul_id' => 'required',
        'vinelab-editor-text' => 'required|max:10000',
      );
      $messages = array(
        'vinelab-editor-text.max' => 'Comment cannot be greater than 10000 characters.',
        'vinelab-editor-text.required' => 'You should really write something in here.'
      );
      $validator = Validator::make($request->all(), $rules, $messages);
      if($validator->passes()) {
        if($request->hasFile('files')){
          $files = $request->file('files');
          $countfiles = count($files);
          foreach ($files as $f) {
            $frules = array(
              'file' => 'mimes:txt,log,pdf,jpeg,jpg,png|max:10000'
            );
            $filevalidator = Validator::make(array('file'=> $f), $frules);
            $filevalidator->getMessageBag()->add('size','Should not be greater than 10MB');
            if ($filevalidator->fails() )
            {
              //dd();
              return redirect()->back()->with('content', $contentmarkdown)
              ->withErrors($filevalidator);
            }
          }
        }
        else {
          $files = null;
          $countfiles = 0;
        }

        $storagePath = 'app/userfiles/uploads/'.Auth::user()->username.'/';
        $token_code = strtoupper(str_random(10).uniqid());

        $comment = new Comment;
        $comment->user()->associate(Auth::user());
        $comment->com_vul_id = $request->com_vul_id;
        $comment->com_text = $contenthtml;
        $comment->save();
        $comment_id = $comment->com_id;
        $comment_vul_id = $request->com_vul_id;

        $initcheck = 0;
        if($countfiles != 0) {
          foreach ($files as $f) {
            if(!is_null($f)) {
              $originalname = $f->getClientOriginalName();
              $newfilename = str_random(6).$originalname;
              $f->move(storage_path($storagePath), $newfilename);
              $f = new Attachment;
              $f->user()->associate(Auth::user());
              $f->at_com_id = $comment_id;
              $f->file_name = $newfilename;
              $f->file_token = $token_code;
              $f->save();
            }
            $initcheck++;
          }
        }

        if(!$countfiles == $initcheck) {
          //one or more files error
          return redirect()->back()->with('content', $contentmarkdown)->with('message','There was an error saving files.');
        }

        if(Auth::user()->role_id == 2) {
          $from_user_id = Auth::user()->id;
          $getvuln = Vulnerability::where('vul_id','=',$request->com_vul_id)->firstOrFail();
          $to_user_id = $getvuln->user_vul_author_id;

          Notifynder::category('user.postedcomment')
              ->from($from_user_id)
              ->to($to_user_id)
              ->url('/vulnerability/'.$request->com_vul_id.'#comment-'.$comment_id)
              ->send();
        }
        elseif(Auth::user()->role_id == 3) {
          $from_user_id = Auth::user()->id;
          $getprod = Vulnerability::with('product')->where('vul_id','=',$request->com_vul_id)->firstOrFail();
          $to_user_id = $getprod->product->user_p_id;

          Notifynder::category('user.postedcomment')
              ->from($from_user_id)
              ->to($to_user_id)
              ->url('/vulnerability/'.$request->com_vul_id.'#comment-'.$comment_id)
              ->send();
        }
        //bulk email to all users who have interacted on this vulnerability report
        $vulninfo = array (
                      'vulnid' => $comment_vul_id,
                      'comment_id' => $comment_id
                    );
        $this->mailtoUsersWithComm($vulninfo);
        return redirect('/vulnerability/'.$request->com_vul_id.'#comment-'.$comment_id)->with('message','Comment Posted!.');
      }
      else {
        return redirect()->back()->withInput()
          ->withErrors($validator);
      }
    }

    /**
     * GET Get all comments with attachments and user details
     * in json format.
     *
     * @param  int $vid
     *
     * @return Response
     */
    protected function getCommentsJson($vid) {
      $comments = Comment::with('user','attachment')->where('com_vul_id','=',$vid)->get();
      $response = array(
          'status' => 'success',
          'comments' => $comments,
      );
      return Response::json($response);
    }

    /**
     * Utility function to send email to users who are part
     * of certain vulnerability report.
     *
     * @param  array $vinfo
     *
     * @return void
     */
    protected function mailtoUsersWithComm($vinfo) {
      $interactingUsers = Comment::with('user')->where('com_vul_id','=',$vinfo['vulnid'])->groupBy('user_com_id')->distinct()->get();
      $vuluid = Vulnerability::where('vul_id','=',$vinfo['vulnid'])->select('vul_unique_id')->firstOrFail();
      $vuid = $vuluid->vul_unique_id;
      //dd();
      foreach ($interactingUsers as $iu) {
        $mailinfo = array (
                      'vulnid' => $vinfo['vulnid'],
                      'comment_id' => $vinfo['comment_id'],
                      'vul_unique_id' => $vuid,
                      'to_user_email' => $iu->user->email,
                      'to_username' => $iu->user->username
                    );
        \Mail::queue('emails.vulns.newcomment', $mailinfo, function($message) use ($mailinfo) {
            $message->to($mailinfo['to_user_email'], ucfirst($mailinfo['to_username']))
                ->subject('New Comment on '.$mailinfo['vul_unique_id']);
        });
      }
    }
}
