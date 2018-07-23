<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Accounts
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Incomes[] $incomes
 * @mixin \Eloquent
 */
class Accounts extends Model
{
    protected $table ='accounts';
    protected $guarded =[];

    //收入\支出
    public function incomes(){
        return $this->hasMany(Incomes::class);
    }
    //转入\转出
    public function transfers(){
        return $this->hasMany(Transfers::class);
    }
}
