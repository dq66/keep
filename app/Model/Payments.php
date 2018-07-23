<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Payments
 *
 * @property-read \App\Model\Customers $customers
 * @property-read \App\Model\Projects $projects
 * @property-read \App\Model\Staffs $staffs
 * @property-read \App\Model\Types $types
 * @property-read \App\User $users
 * @mixin \Eloquent
 */
class Payments extends Model
{
    protected $table = 'payments';
    protected $guarded = [];

    //类型
    public function types(){
        return $this->belongsTo(Types::class);
    }

    //生意伙伴
    public function customers(){
        return $this->belongsTo(Customers::class);
    }

    //业务项目
    public function projects(){
        return $this->belongsTo(Projects::class);
    }

    //经手人
    public function staffs(){
        return $this->belongsTo(Staffs::class);
    }

    //记账人
    public function users(){
        return $this->hasOne(User::class,'id','users_id');
    }

}
