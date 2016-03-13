<?php

namespace openvidsys;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Fenos\Notifynder\Notifable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    use Notifable;

    public function resprofile()
    {
        return $this->hasOne('openvidsys\Model\ResProfile','user_res_id');
    }

    public function orgprofile()
    {
        return $this->hasOne('openvidsys\Model\OrgProfile','user_org_id');
    }

    /**
     * Relationship : user one-to-many files
     */
    public function fileupload()
    {
      return $this->hasMany('openvidsys\Model\FileUpload','file_user_id');
    }

    public function product()
    {
        return $this->hasMany('openvidsys\Model\Product','user_p_id');
    }

    public function comment()
    {
        return $this->hasMany('openvidsys\Model\Comment','user_com_id');
    }
}
