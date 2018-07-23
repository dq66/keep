<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Incomes
 *
 * @property-read \App\Model\Accounts $accounts
 * @property-read \App\Model\Customers $customers
 * @property-read \App\Model\Projects $projects
 * @property-read \App\Model\Staffs $staffs
 * @property-read \App\Model\Types $types
 * @property-read \App\User $users
 * @mixin \Eloquent
 */
class Incomes extends Model
{
    protected $table = 'incomes';
    protected $guarded = [];

    //分类
    public function types(){
        return $this->belongsTo(Types::class);
    }

    //资产账户
    public function accounts(){
        return $this->belongsTo(Accounts::class);
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
    public function users()
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }

}
