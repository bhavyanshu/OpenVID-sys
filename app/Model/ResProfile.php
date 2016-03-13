<?php

namespace openvidsys\Model;

use Illuminate\Database\Eloquent\Model;

class ResProfile extends Model
{
  protected $table = 'resprofiles';
  protected $foreignKey = 'user_res_id';
  protected $primaryKey = 'res_id';

  public function user()
  {
      return $this->belongsTo('openvidsys\User','user_res_id');
  }
}
