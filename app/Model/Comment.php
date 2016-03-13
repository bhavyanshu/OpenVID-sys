<?php

namespace openvidsys\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $table = 'comments';
  protected $primaryKey = 'com_id';

  public function user()
  {
      return $this->belongsTo('openvidsys\User','user_com_id');
  }

  public function vulnerability()
  {
      return $this->belongsTo('openvidsys\Model\Vulnerability','com_vul_id');
  }

  public function getCreatedAtAttribute($value)
  {
      return date('D d-m-Y h:ia', strtotime($value));
  }

  public function attachment() {
      return $this->hasMany('openvidsys\Model\Attachment','at_com_id');
  }
}
