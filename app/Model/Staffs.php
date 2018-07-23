<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Staffs
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Incomes[] $incomes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Projects[] $payments
 * @mixin \Eloquent
 */
class Staffs extends Model
{
    protected $table = 'staffs';
    protected $guarded = [];


    //收入\支出
    public function incomes(){
        return $this->hasMany(Incomes::class);
    }

    //应收\应付
    public function payments(){
        return $this->hasMany(Projects::class);
    }
}
