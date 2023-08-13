<?php

namespace App\Models\WithDrawals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;
   /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id','store_id','user_id','role_type','description','transaction_type','order_total_amount','order_sub_amount','order_discount_amount','order_delivery_amount','amount','current_balance','currency','status'
    ];
}
