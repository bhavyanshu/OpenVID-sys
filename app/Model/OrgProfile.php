<?php

namespace openvidsys\Model;

use Illuminate\Database\Eloquent\Model;

class OrgProfile extends Model
{
  protected $table = 'orgprofiles';
  protected $foreignKey = 'user_org_id';
  protected $primaryKey = 'org_id';

  public function user()
  {
      return $this->belongsTo('openvidsys\User','user_org_id');
  }
}
