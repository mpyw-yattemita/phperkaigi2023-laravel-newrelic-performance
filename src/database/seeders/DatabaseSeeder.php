<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Prefecture;
use Illuminate\Database\Seeder;
use stdClass;

class DatabaseSeeder extends Seeder
{
    protected mixed $data;

    public function run(): void
    {
        $this->data = json_decode(file_get_contents(__DIR__ . '/population-stats.min.json'));

        foreach ($this->data->prefectures ?? [] as $prefecture) {
            $prefectureModel = Prefecture::query()
                ->create($this->commonFields($prefecture));

            foreach ($prefecture->cities ?? [] as $city) {
                $cityModel = City::query()
                    ->create(
                        ['prefecture_id' => $prefectureModel->id]
                        + $this->commonFields($city),
                    );

                foreach ($city->districts ?? [] as $district) {
                    District::query()
                        ->create(
                            ['city_id' => $cityModel->id]
                            + $this->commonFields($district),
                        );
                }
            }
        }
    }

    private function commonFields(stdClass $record): array {
        return [
            'name' => $record->name ?? null,
            'all' => $record->all ?? null,
            'male' => $record->male ?? null,
            'female' => $record->female ?? null,
            'at_2015' => $record->at_2015 ?? null,
            'compared_to_2015' => $record->compared_to_2015 ?? null,
            'percentage_compared_to_2015' => $record->percentage_compared_to_2015 ?? null,
            'density' => $record->density ?? null,
            'average_age' => $record->average_age ?? null,
            'median_age' => $record->median_age ?? null,
            'under_14' => $record->under_14 ?? null,
            'under_64' => $record->under_64 ?? null,
            'over_65' => $record->over_65 ?? null,
            'percentage_under_14' => $record->percentage_under_14 ?? null,
            'percentage_under_64' => $record->percentage_under_64 ?? null,
            'percentage_over_65' => $record->percentage_over_65 ?? null,
            'male_under_14' => $record->male_under_14 ?? null,
            'male_under_64' => $record->male_under_64 ?? null,
            'male_over_65' => $record->male_over_65 ?? null,
            'male_percentage_under_14' => $record->male_percentage_under_14 ?? null,
            'male_percentage_under_64' => $record->male_percentage_under_64 ?? null,
            'male_percentage_over_65' => $record->male_percentage_over_65 ?? null,
            'female_under_14' => $record->female_under_14 ?? null,
            'female_under_64' => $record->female_under_64 ?? null,
            'female_over_65' => $record->female_over_65 ?? null,
            'female_percentage_under_14' => $record->female_percentage_under_14 ?? null,
            'female_percentage_under_64' => $record->female_percentage_under_64 ?? null,
            'female_percentage_over_65' => $record->female_percentage_over_65 ?? null,
        ];
    }
}
