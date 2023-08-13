<?php

namespace App\Models\Worlds;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Worlds\Cities;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'country_ios_code',
        'nationality',
        'order',
        'is_default',
        'status',
    ];

   /**
     * hasMany relation with Post
     *
     * @return BelongsTo
     */
    public function cities(){
        return $this->hasMany(Cities::class, 'country_id', 'id')->where('status', 1);    
    }
}
