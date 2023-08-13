<?php

namespace App\Models\Worlds;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Worlds\Country;

class Cities extends Model
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
        'country_id',
        'state_id',
        'order',
        'is_default',
        'status',
    ];

  /**
     * @return HasMany
     * @description get the detail associated with the post
     */
    
    public function cities(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }


}
