<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read Prefecture $prefecture
 * @property-read string $sortable_id
 */
class City extends Model
{
    protected $guarded = [];

    protected $casts = [
        'prefecture_id' => 'int',
        'name' => 'string',
        'all' => 'int',
        'male' => 'int',
        'female' => 'int',
        'at_2015' => 'int',
        'compared_to_2015' => 'int',
        'percentage_compared_to_2015' => 'float',
        'density' => 'float',
        'average_age' => 'float',
        'median_age' => 'float',
        'under_14' => 'int',
        'under_64' => 'int',
        'over_65' => 'int',
        'percentage_under_14' => 'float',
        'percentage_under_64' => 'float',
        'percentage_over_65' => 'float',
        'male_under_14' => 'int',
        'male_under_64' => 'int',
        'male_over_65' => 'int',
        'male_percentage_under_14' => 'float',
        'male_percentage_under_64' => 'float',
        'male_percentage_over_65' => 'float',
        'female_under_14' => 'int',
        'female_under_64' => 'int',
        'female_over_65' => 'int',
        'female_percentage_under_14' => 'float',
        'female_percentage_under_64' => 'float',
        'female_percentage_over_65' => 'float',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function sortableId(): Attribute
    {
        return new Attribute(
            get: fn (): string => sprintf("%04d-%04d-0000", $this->prefecture->id, $this->id),
        );
    }

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class)->orderBy('id');
    }
}
