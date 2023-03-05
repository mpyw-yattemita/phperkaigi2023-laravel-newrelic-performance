<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Validators\LaravelValidator;
use App\Http\Controllers\Validators\PurePHPValidator;
use App\Http\Controllers\Validators\WildcardLessLaravelValidator;
use App\Models\City;
use App\Models\District;
use App\Models\Prefecture;
use Generator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Update
{
    public function __construct(
        private readonly ResponseFactory $response,
        private readonly PurePHPValidator $purePHPValidator,
        private readonly LaravelValidator $laravelValidator,
        private readonly WildcardLessLaravelValidator $wildcardLessLaravelValidator,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $this->configNewRelicTransaction($request);

        if ($request->query('with_pure_php_validation')) {
            $payload = ($this->purePHPValidator)($request);
        } elseif ($request->query('with_laravel_validation')) {
            $payload = ($this->laravelValidator)($request);
        } elseif ($request->query('with_wildcard_less_laravel_validation')) {
            $payload = ($this->wildcardLessLaravelValidator)($request);
        } else {
            $payload = $request->json('data', []);
        }

        $this->performUpdate($payload);

        return $this->response->noContent();
    }

    protected function configNewRelicTransaction(Request $request): void
    {
        if (!extension_loaded('newrelic')) {
            return;
        }

        $targets = [];

        if ($prefectures = (array)$request->input('data', [])) {
            $targets[] = 'prefectures';
        }
        if ($cities = array_merge(...array_column($prefectures, 'cities'))) {
            $targets[] = 'cities';
        }
        if (array_merge(...array_column($cities, 'districts'))) {
            $targets[] = 'districts';
        }

        newrelic_name_transaction(sprintf(
            'Update|Validator=%s;Target={%s}',
            match (true) {
                (bool)$request->query('with_pure_php_validation') => 'PurePHP',
                (bool)$request->query('with_laravel_validation')  => 'Laravel',
                (bool)$request->query('with_wildcard_less_laravel_validation')  => 'WildcardLessLaravel',
                default => 'None',
            },
            implode(',', $targets),
        ));
    }

    private function performUpdate(array $payload): void
    {
        $valuesArrayGroups = [
            Prefecture::class => [],
            City::class => [],
            District::class => [],
        ];

        foreach ($this->generateValues($payload) as $model => $values) {
            $valuesArrayGroups[$model][] = $values;
            if (count($valuesArrayGroups[$model]) >= 1000) {
                $this->runBulkUpdateQuery($model, $valuesArrayGroups[$model]);
                $valuesArrayGroups[$model] = [];
            }
        }
        foreach ($valuesArrayGroups as $model => $valuesArray) {
            if ($valuesArray) {
                $this->runBulkUpdateQuery($model, $valuesArray);
            }
        }
    }

    private function generateValues(array $payload): Generator
    {
        $keys = array_flip([
            'id',
            'all',
            'male',
            'female',
            'at_2015',
            'compared_to_2015',
            'percentage_compared_to_2015',
            'density',
            'average_age',
            'median_age',
            'under_14',
            'under_64',
            'over_65',
            'percentage_under_14',
            'percentage_under_64',
            'percentage_over_65',
            'male_under_14',
            'male_under_64',
            'male_over_65',
            'male_percentage_under_14',
            'male_percentage_under_64',
            'male_percentage_over_65',
            'female_under_14',
            'female_under_64',
            'female_over_65',
            'female_percentage_under_14',
            'female_percentage_under_64',
            'female_percentage_over_65',
        ]);

        foreach ($payload['data'] ?? [] as $prefecture) {
            yield Prefecture::class => array_intersect_key($prefecture, $keys);
            foreach ($prefecture['cities'] ?? [] as $city) {
                yield City::class => array_intersect_key($city, $keys);
                foreach ($city['districts'] ?? [] as $district) {
                    yield District::class => array_intersect_key($district, $keys);
                }
            }
        }
    }

    private function runBulkUpdateQuery(string $model, array $valuesArray): void
    {
        $ids = array_column($valuesArray, 'id');
        $holders = array_fill(0, count($ids), '?');
        $bindings = [];
        $sets = [];

        /** @var Model $model */
        $query = $model::query();

        foreach ($valuesArray[0] as $field => $_) {
            if ($field === 'id') {
                continue;
            }
            $sets[] = sprintf(
                '%1$s = ELT(FIELD(id, %2$s), %2$s)',
                $query->getGrammar()->wrap($field),
                implode(', ', $holders),
            );
            $bindings = [
                ...$bindings,
                ...$ids,
                ...array_column($valuesArray, $field),
            ];
        }
        $bindings = [...$bindings, ...$ids];

        $sql = implode(' ', [
            'UPDATE',
            $query->getGrammar()->wrap($query->getModel()->getTable()),
            'SET',
            implode(', ', $sets),
            'WHERE id IN (',
            implode(', ', $holders),
            ')',
        ]);

        DB::affectingStatement($sql, $bindings);
    }
}
