<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function orders()
    {
       
        return $this->hasMany(OrdersProduct::class,'order_id');
    }
}
