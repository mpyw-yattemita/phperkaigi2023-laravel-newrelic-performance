<?php

declare(strict_types=1);

namespace App\Http\Controllers\Validators;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WildcardLessLaravelValidator
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
        $rules = [
            'data' => 'array',
        ];

        foreach ((array)($request->json()->all()['data'] ?? []) as $i => $prefecture) {
            $rules += [
                "data.{$i}" => 'array',
                "data.{$i}.id" => 'required|integer',
                "data.{$i}.all" => 'bail|nullable|integer|gte:0',
                "data.{$i}.male" => 'bail|nullable|integer|gte:0',
                "data.{$i}.female" => 'bail|nullable|integer|gte:0',
                "data.{$i}.at_2015" => 'bail|nullable|integer|gte:0',
                "data.{$i}.compared_to_2015" => 'nullable|integer',
                "data.{$i}.percentage_compared_to_2015" => 'nullable|numeric',
                "data.{$i}.density" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.average_age" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.median_age" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.under_14" => 'bail|nullable|integer|gte:0',
                "data.{$i}.under_64" => 'bail|nullable|integer|gte:0',
                "data.{$i}.over_65" => 'bail|nullable|integer|gte:0',
                "data.{$i}.percentage_under_14" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.percentage_under_64" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.percentage_over_65" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.male_under_14" => 'bail|nullable|integer|gte:0',
                "data.{$i}.male_under_64" => 'bail|nullable|integer|gte:0',
                "data.{$i}.male_over_65" => 'bail|nullable|integer|gte:0',
                "data.{$i}.male_percentage_under_14" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.male_percentage_under_64" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.male_percentage_over_65" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.female_under_14" => 'bail|nullable|integer|gte:0',
                "data.{$i}.female_under_64" => 'bail|nullable|integer|gte:0',
                "data.{$i}.female_over_65" => 'bail|nullable|integer|gte:0',
                "data.{$i}.female_percentage_under_14" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.female_percentage_under_64" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.female_percentage_over_65" => 'bail|nullable|numeric|gte:0',
                "data.{$i}.cities" => 'array',
            ];
            foreach ((array)($prefecture['cities'] ?? []) as $j => $city) {
                $rules += [
                    "data.{$i}.cities.{$j}" => 'array',
                    "data.{$i}.cities.{$j}.id" => 'required|integer',
                    "data.{$i}.cities.{$j}.all" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.male" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.female" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.at_2015" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.compared_to_2015" => 'nullable|integer',
                    "data.{$i}.cities.{$j}.percentage_compared_to_2015" => 'nullable|numeric',
                    "data.{$i}.cities.{$j}.density" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.average_age" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.median_age" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.under_14" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.under_64" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.over_65" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.percentage_under_14" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.percentage_under_64" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.percentage_over_65" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.male_under_14" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.male_under_64" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.male_over_65" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.male_percentage_under_14" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.male_percentage_under_64" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.male_percentage_over_65" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.female_under_14" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.female_under_64" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.female_over_65" => 'bail|nullable|integer|gte:0',
                    "data.{$i}.cities.{$j}.female_percentage_under_14" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.female_percentage_under_64" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.female_percentage_over_65" => 'bail|nullable|numeric|gte:0',
                    "data.{$i}.cities.{$j}.districts" => 'array',
                ];
                foreach ((array)($city['districts'] ?? []) as $k => $district) {
                    $rules += [
                        "data.{$i}.cities.{$j}.districts.{$k}" => 'array',
                        "data.{$i}.cities.{$j}.districts.{$k}.id" => 'required|integer',
                        "data.{$i}.cities.{$j}.districts.{$k}.all" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.male" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.female" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.at_2015" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.compared_to_2015" => 'nullable|integer',
                        "data.{$i}.cities.{$j}.districts.{$k}.percentage_compared_to_2015" => 'nullable|numeric',
                        "data.{$i}.cities.{$j}.districts.{$k}.density" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.average_age" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.median_age" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.under_14" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.under_64" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.over_65" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.percentage_under_14" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.percentage_under_64" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.percentage_over_65" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.male_under_14" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.male_under_64" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.male_over_65" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.male_percentage_under_14" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.male_percentage_under_64" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.male_percentage_over_65" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.female_under_14" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.female_under_64" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.female_over_65" => 'bail|nullable|integer|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.female_percentage_under_14" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.female_percentage_under_64" => 'bail|nullable|numeric|gte:0',
                        "data.{$i}.cities.{$j}.districts.{$k}.female_percentage_over_65" => 'bail|nullable|numeric|gte:0',
                    ];
                }
            }
        }

        $validator = $this->validator->make($request->json()->all(), $rules);

        return $validator->validated();
    }
}
