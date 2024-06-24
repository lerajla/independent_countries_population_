<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CountryRegion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
    ];

      /**
      * Get the country population level associated with the country region.
     *
     * @return HasOne
     */
    public function countryPopulationLevel(): HasOne
    {
        return $this->hasOne(CountryPopulationLevel::class, 'country_region_id');
    }

}
