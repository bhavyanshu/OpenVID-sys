<?php

namespace openvidsys\Model;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'userfiles';
  protected $foreignKey = 'file_user_id';
  protected $primaryKey = 'file_id';

  /**
   * Relationship : user one-to-one profile
   */
  public function user()
  {
      return $this->belongsTo('openvidsys\User','file_user_id');
  }
}
