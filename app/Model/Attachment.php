<?php

namespace openvidsys\Model;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
  protected $table = 'attachments';
  protected $primaryKey = 'at_id';

  public function attachment() {
      return $this->belongsTo('openvidsys\Model\Comment','at_com_id');
  }

  public function user() {
      return $this->belongsTo('openvidsys\User','user_at_id');
  }
}
