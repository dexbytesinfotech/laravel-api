<?php

namespace App\Models\WithDrawals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrwals extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_id',
        'user_id',
        'revenue_ids',
        'order_ids',
        'transaction_id',
        'transaction_type',
        'last_balance',
        'amount',
        'current_balance',
        'bank_info',
        'image',
        'status'
    ];
    /**
     * @return BelongsTo
     * @description get the detail associated with the user
     */

     public function user(): BelongsTo
     {
         return $this->belongsTo(User::class);
     }
}
