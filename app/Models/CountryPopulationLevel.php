<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class CountryPopulationLevel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'count_value',
        'avg_population',
        'population_level_id',
        'country_region_id'
    ];

    /**
     * Get the region that belongs to country population level.
     *
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(CountryRegion::class, 'country_region_id');
    }

    /**
     * Get the population level that belongs to country population level.
     *
     * @return BelongsTo
     */
    public function populationLevel(): BelongsTo
    {
        return $this->belongsTo(PopulationLevel::class, 'population_level_id');
    }

      /**
     * Scope a query to only include records of a given level.
     */
    public function scopeOfPopulationLevel(Builder $query, int $populationLevelId): void
    {
        $query->where('population_level_id', $populationLevelId);
    }

    /**
     * Scope a query to only include records of a given region.
     */
    public function scopeOfRegion(Builder $query, int $countryRegionId): void
    {
        $query->where('country_region_id', $countryRegionId);
    }
}
