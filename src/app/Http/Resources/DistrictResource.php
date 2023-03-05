<?php

namespace App\Http\Resources;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read District $resource
 */
class DistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'city_id' => $this->resource->city_id,
            'sortable_id' => $this->resource->sortable_id,
            'name' => $this->resource->name,
            'all' => $this->resource->all,
            'male' => $this->resource->male,
            'female' => $this->resource->female,
            'at_2015' => $this->resource->at_2015,
            'compared_to_2015' => $this->resource->compared_to_2015,
            'percentage_compared_to_2015' => $this->resource->percentage_compared_to_2015,
            'density' => $this->resource->density,
            'average_age' => $this->resource->average_age,
            'median_age' => $this->resource->median_age,
            'under_14' => $this->resource->under_14,
            'under_64' => $this->resource->under_64,
            'over_65' => $this->resource->over_65,
            'percentage_under_14' => $this->resource->percentage_under_14,
            'percentage_under_64' => $this->resource->percentage_under_64,
            'percentage_over_65' => $this->resource->percentage_over_65,
            'male_under_14' => $this->resource->male_under_14,
            'male_under_64' => $this->resource->male_under_64,
            'male_over_65' => $this->resource->male_over_65,
            'male_percentage_under_14' => $this->resource->male_percentage_under_14,
            'male_percentage_under_64' => $this->resource->male_percentage_under_64,
            'male_percentage_over_65' => $this->resource->male_percentage_over_65,
            'female_under_14' => $this->resource->female_under_14,
            'female_under_64' => $this->resource->female_under_64,
            'female_over_65' => $this->resource->female_over_65,
            'female_percentage_under_14' => $this->resource->female_percentage_under_14,
            'female_percentage_under_64' => $this->resource->female_percentage_under_64,
            'female_percentage_over_65' => $this->resource->female_percentage_over_65,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
