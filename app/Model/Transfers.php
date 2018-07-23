<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Transfers extends Model
{
    protected $table = 'transfers';
    protected $guarded = [];

    //账户
    public function accounts(){
        return $this->belongsTo(Accounts::class);
    }

    //记账人
    public function users()
    {
        return $this->hasOne(User::class, 'id', 'users_id');
    }
}
