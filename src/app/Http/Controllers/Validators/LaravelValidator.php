<?php

declare(strict_types=1);

namespace App\Http\Controllers\Validators;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LaravelValidator
{
    public function __construct(
        private readonly Factory $validator,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): array
    {
        $validator = $this->validator->make($request->json()->all(), [
            'data' => 'array',
            'data.*' => 'array',
            'data.*.id' => 'required|integer',
            'data.*.all' => 'bail|nullable|integer|gte:0',
            'data.*.male' => 'bail|nullable|integer|gte:0',
            'data.*.female' => 'bail|nullable|integer|gte:0',
            'data.*.at_2015' => 'bail|nullable|integer|gte:0',
            'data.*.compared_to_2015' => 'nullable|integer',
            'data.*.percentage_compared_to_2015' => 'nullable|numeric',
            'data.*.density' => 'bail|nullable|numeric|gte:0',
            'data.*.average_age' => 'bail|nullable|numeric|gte:0',
            'data.*.median_age' => 'bail|nullable|numeric|gte:0',
            'data.*.under_14' => 'bail|nullable|integer|gte:0',
            'data.*.under_64' => 'bail|nullable|integer|gte:0',
            'data.*.over_65' => 'bail|nullable|integer|gte:0',
            'data.*.percentage_under_14' => 'bail|nullable|numeric|gte:0',
            'data.*.percentage_under_64' => 'bail|nullable|numeric|gte:0',
            'data.*.percentage_over_65' => 'bail|nullable|numeric|gte:0',
            'data.*.male_under_14' => 'bail|nullable|integer|gte:0',
            'data.*.male_under_64' => 'bail|nullable|integer|gte:0',
            'data.*.male_over_65' => 'bail|nullable|integer|gte:0',
            'data.*.male_percentage_under_14' => 'bail|nullable|numeric|gte:0',
            'data.*.male_percentage_under_64' => 'bail|nullable|numeric|gte:0',
            'data.*.male_percentage_over_65' => 'bail|nullable|numeric|gte:0',
            'data.*.female_under_14' => 'bail|nullable|integer|gte:0',
            'data.*.female_under_64' => 'bail|nullable|integer|gte:0',
            'data.*.female_over_65' => 'bail|nullable|integer|gte:0',
            'data.*.female_percentage_under_14' => 'bail|nullable|numeric|gte:0',
            'data.*.female_percentage_under_64' => 'bail|nullable|numeric|gte:0',
            'data.*.female_percentage_over_65' => 'bail|nullable|numeric|gte:0',

            'data.*.cities' => 'array',
            'data.*.cities.*' => 'array',
            'data.*.cities.*.id' => 'required|integer',
            'data.*.cities.*.all' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.male' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.female' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.at_2015' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.compared_to_2015' => 'nullable|integer',
            'data.*.cities.*.percentage_compared_to_2015' => 'nullable|numeric',
            'data.*.cities.*.density' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.average_age' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.median_age' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.under_14' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.under_64' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.over_65' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.percentage_under_14' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.percentage_under_64' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.percentage_over_65' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.male_under_14' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.male_under_64' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.male_over_65' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.male_percentage_under_14' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.male_percentage_under_64' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.male_percentage_over_65' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.female_under_14' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.female_under_64' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.female_over_65' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.female_percentage_under_14' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.female_percentage_under_64' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.female_percentage_over_65' => 'bail|nullable|numeric|gte:0',

            'data.*.cities.*.districts' => 'array',
            'data.*.cities.*.districts.*' => 'array',
            'data.*.cities.*.districts.*.id' => 'required|integer',
            'data.*.cities.*.districts.*.all' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.male' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.female' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.at_2015' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.compared_to_2015' => 'nullable|integer',
            'data.*.cities.*.districts.*.percentage_compared_to_2015' => 'nullable|numeric',
            'data.*.cities.*.districts.*.density' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.average_age' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.median_age' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.under_14' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.under_64' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.over_65' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.percentage_under_14' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.percentage_under_64' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.percentage_over_65' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.male_under_14' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.male_under_64' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.male_over_65' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.male_percentage_under_14' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.male_percentage_under_64' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.male_percentage_over_65' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.female_under_14' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.female_under_64' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.female_over_65' => 'bail|nullable|integer|gte:0',
            'data.*.cities.*.districts.*.female_percentage_under_14' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.female_percentage_under_64' => 'bail|nullable|numeric|gte:0',
            'data.*.cities.*.districts.*.female_percentage_over_65' => 'bail|nullable|numeric|gte:0',
        ]);

        return $validator->validated();
    }
}
