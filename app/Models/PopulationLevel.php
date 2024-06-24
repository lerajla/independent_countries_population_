<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PopulationLevel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
    ];

     /**
      * Get the country population level associated with the population level.
     *
     * @return HasOne
     */
     public function countryPopulationLevel(): HasOne
     {
       return $this->hasOne(CountryPopulationLevel::class, 'population_level_id');
     }
}
