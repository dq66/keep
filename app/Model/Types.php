<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Jiaxincui\ClosureTable\Traits\ClosureTable;

/**
 * App\Model\Types
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Incomes[] $incomes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Payments[] $payments
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Types isolated()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Types onlyRoot()
 * @mixin \Eloquent
 */
class Types extends Model
{
    use ClosureTable;
    protected $table = 'types';
    protected $closureTable = 'types_closure';
    protected $parentColumn = 'parent';
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
