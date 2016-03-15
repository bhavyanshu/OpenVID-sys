<?php
namespace openvidsys\Common;

use openvidsys\User;
use openvidsys\Model\ResProfile;
use openvidsys\Model\OrgProfile;

use Illuminate\Support\Facades\Input;

class Utility {

  //XSS prevent
  public static function killXSS()
  {
    $sanitized = static::cleanArray(Input::get());
    Input::merge($sanitized);
  }

  public static function cleanArray($array)
  {
    $result = array();
    foreach ($array as $key => $value) {
        $key = strip_tags($key);
        if (is_array($value)) {
            $result[$key] = static::cleanArray($value);
        } else {
            $result[$key] = trim(strip_tags($value));
        }
    }
    return $result;
  }

  //Helper function - Get profile by role - table resprofile, orgprofile
  public static function getProfile($gotuser) {
    if ($gotuser->role_id==2) {
      $assocprofile = User::with('orgprofile')->find($gotuser->id)->orgprofile;
      return $assocprofile;
    }
    elseif ($gotuser->role_id==3) {
      $assocprofile = User::with('resprofile')->find($gotuser->id)->resprofile;
      return $assocprofile;
    }
  }

  public static function computeScore($getArr) {
    $vultype = $getArr['vul_type'];
    $vulcomplex = $getArr['vul_complexity'];
    $vulauth = $getArr['vul_auth'];
    $vulconf = $getArr['vul_confidentiality'];
    $vulintegrity = $getArr['vul_integrity'];
    $vulperformance = $getArr['vul_performance'];
    $vulaccess = $getArr['vul_access'];
    $totalScore = 0;

    /** vul_type
    *0 ->'Bypass authentication/restriction',
    *1 ->'Cross Site Scripting',
    *2 ->'Denial of service',
    *3 ->'Execute arbitrary code',
    *4 ->'Gain Privileges',
    *5 ->'Directory Traversal',
    *6 ->'Http Response Splitting',
    *7 ->'Memory Corruption',
    *8 ->'Overflow (stack/heap/other)',
    *9 ->'CSRF',
    *10 ->'File Inclusion',
    *11 ->'SQL Injection',
    *12 ->'Unrestricted Critical Information Access'
    */
    if($vultype == 0) {
      $totalScore += 3;
    }
    elseif($vultype == 1) {
      $totalScore += 1;
    }
    elseif($vultype == 2) {
      $totalScore += 1;
    }
    elseif($vultype == 3) {
      $totalScore += 1;
    }
    elseif($vultype == 4) {
      $totalScore += 3;
    }
    elseif($vultype == 5) {
      $totalScore += 2;
    }
    elseif($vultype == 6) {
      $totalScore += 1;
    }
    elseif($vultype == 7) {
      $totalScore += 1;
    }
    elseif($vultype == 8) {
      $totalScore += 2;
    }
    elseif($vultype == 9) {
      $totalScore += 2;
    }
    elseif($vultype == 10) {
      $totalScore += 2;
    }
    elseif($vultype == 11) {
      $totalScore += 3;
    }
    elseif($vultype == 12) {
      $totalScore += 3;
    }
    else {
      $totalScore = 0;
    }

    //0->low (easy to access), 1->medium, 2->high
    if($vulcomplex == 0) {
      $totalScore += 1.5;
    }
    elseif($vulcomplex == 1) {
      $totalScore += 1;
    }
    elseif($vulcomplex == 2) {
      $totalScore += .5;
    }
    else {
      $totalScore = 0;
    }

    //0->not required, 1->required
    if($vulauth == 0 || $vulauth == 1) {
      $totalScore += .5;
    }
    else {
      $totalScore = 0;
    }

    //0->none, 1->partial, 2->complete
    if($vulconf == 0) {
      $totalScore += 0;
    }
    elseif($vulconf == 1) {
      $totalScore += .5;
    }
    elseif($vulconf == 2) {
      $totalScore += 1;
    }
    else {
      $totalScore = 0;
    }

    //0->none, 1->partial, 2->complete
    if($vulintegrity == 0) {
      $totalScore += 0;
    }
    elseif($vulintegrity == 1) {
      $totalScore += .5;
    }
    elseif($vulintegrity == 2) {
      $totalScore += 1;
    }
    else {
      $totalScore = 0;
    }

    //0->none, 1->partial, 2->complete
    if($vulperformance == 0) {
      $totalScore += 0;
    }
    elseif($vulperformance == 1) {
      $totalScore += .5;
    }
    elseif($vulperformance == 2) {
      $totalScore += 1;
    }
    else {
      $totalScore = 0;
    }

    //0->none, 1->admin, 2->user, 3->other
    if($vulaccess == 0) {
      $totalScore += 0;
    }
    elseif($vulaccess == 1) {
      $totalScore += 2;
    }
    elseif($vulaccess == 2) {
      $totalScore += 1;
    }
    else {
      $totalScore += 1;
    }

    return $totalScore;
  }

  public static function productGraphs($vulnerabilities) {

    $vulns = $vulnerabilities;
    $low = 0;
    $medium = 0;
    $high = 0;

    $bypassauth = 0; // *0 ->'Bypass authentication/restriction',
    $css = 0;        // *1 ->'Cross Site Scripting',
    $dos = 0;        // *2 ->'Denial of service',
    $eac = 0;        // *3 ->'Execute arbitrary code',
    $gp = 0;         // *4 ->'Gain Privileges',
    $dt = 0;         // *5 ->'Directory Traversal',
    $hrs = 0;        // *6 ->'Http Response Splitting',
    $mc = 0;         // *7 ->'Memory Corruption',
    $overflow = 0;   // *8 ->'Overflow (stack/heap/other)',
    $csrf = 0;       // *9 ->'CSRF',
    $fi = 0;         // *10 ->'File Inclusion',
    $sqli = 0;       // *11 ->'SQL Injection',
    $cia = 0;        // *12 ->'Unrestricted Critical Information Access'

    foreach ($vulns as $v) {

      if($v->vul_type == 0) {
        $bypassauth++;
      }
      elseif($v->vul_type == 1) {
        $css++;
      }
      elseif($v->vul_type == 2) {
        $dos++;
      }
      elseif($v->vul_type == 3) {
        $eac++;
      }
      elseif($v->vul_type == 4) {
        $gp++;
      }
      elseif($v->vul_type == 5) {
        $dt++;
      }
      elseif($v->vul_type == 6) {
        $hrs++;
      }
      elseif($v->vul_type == 7) {
        $mc++;
      }
      elseif($v->vul_type == 8) {
        $overflow++;
      }
      elseif($v->vul_type == 9) {
        $csrf++;
      }
      elseif($v->vul_type == 10) {
        $fi++;
      }
      elseif($v->vul_type == 11) {
        $sqli++;
      }
      elseif($v->vul_type == 12) {
        $cia++;
      }

      $int_threatlevel = round($v->threat_level);
      if($int_threatlevel > 0 && $int_threatlevel <= 3) {
        $low++;
      }
      elseif($int_threatlevel > 3 && $int_threatlevel <= 7) {
        $medium++;
      }
      elseif($int_threatlevel > 7 && $int_threatlevel <= 10) {
        $high++;
      }
    }
    $vultypes = array (
      'bpa'=>$bypassauth, // *0 ->'Bypass authentication/restriction',
      'css'=>$css,        // *1 ->'Cross Site Scripting',
      'dos'=>$dos,      // *2 ->'Denial of service',
      'eac'=>$eac,      // *3 ->'Execute arbitrary code',
      'gp'=>$gp,        // *4 ->'Gain Privileges',
      'dt'=>$dt,        // *5 ->'Directory Traversal',
      'hrs'=>$hrs,       // *6 ->'Http Response Splitting',
      'mc'=>$mc,       // *7 ->'Memory Corruption',
      'overflow'=>$overflow,   // *8 ->'Overflow (stack/heap/other)',
      'csrf'=>$csrf,      // *9 ->'CSRF',
      'fi'=>$fi,      // *10 ->'File Inclusion',
      'sqli'=>$sqli,      // *11 ->'SQL Injection',
      'scia'=>$cia,       // *12 ->'Unrestricted Critical Information Access'
    );
    $threat_values = array(
      'low'=>$low,
      'medium'=>$medium,
      'high'=>$high,
    );
    $vul_meta = array(
      'getvultype' => $vultypes,
      'getthreatvalues' => $threat_values
    );
    return $vul_meta;
  }
}
