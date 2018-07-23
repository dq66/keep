<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Customers
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Incomes[] $incomes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Payments[] $payments
 * @mixin \Eloquent
 */
class Customers extends Model
{
    protected $table = 'customers';
    protected $guarded = [];

    //收入\支出
    public function incomes(){
        return $this->hasMany(Incomes::class);
    }

    //应收\应付
    public function payments(){
        return $this->hasMany(Payments::class);
    }
}
