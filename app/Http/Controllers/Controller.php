<?php
/**
 * OpenVID-sys
 *
 * @copyright 2016 Bhavyanshu Parasher (https://bhavyanshu.me)
 * @license   http://www.apache.org/licenses/LICENSE-2.0.txt Apache License
 * @link      https://bhavyanshu.me/pages/openvid_sys/
 */
namespace openvidsys\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Extending BaseController
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
