<?php

namespace openvidsys\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $table = 'products';
  protected $foreignKey = 'user_p_id';
  protected $primaryKey = 'p_id';

  public function user()
  {
      return $this->belongsTo('openvidsys\User','user_p_id');
  }

  public function vulnerability()
  {
      return $this->hasMany('openvidsys\Model\Vulnerability','vul_prod_id');
  }
}
