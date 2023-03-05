<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PrefectureResource;
use App\Models\Prefecture;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class View
{
    public function __invoke(Request $request): ResourceCollection
    {
        $this->configNewRelicTransaction($request);

        $query = Prefecture::query()->orderBy('id');

        if ($request->query('with_eager_children_cities')) {
            $query->with('cities');
            if ($request->query('with_eager_parent')) {
                $query->with('cities.prefecture');
            }
        }
        if ($request->query('with_eager_children_districts')) {
            $query->with('cities.districts');
            if ($request->query('with_eager_parent')) {
                $query
                    ->with('cities.districts.city')
                    ->with('cities.districts.city.prefecture');
            }
        }

        return PrefectureResource::collection($query->get());
    }

    private function configNewRelicTransaction(Request $request): void
    {
        if (!extension_loaded('newrelic')) {
            return;
        }

        $options = [
            'lazy' => [],
            'eager' => [],
        ];

        if ($request->query('with_lazy_children_cities')) {
            $options['lazy'][] = 'cities';
        }
        if ($request->query('with_lazy_children_districts')) {
            $options['lazy'][] = 'districts';
        }
        if ($request->query('with_eager_children_cities')) {
            $options['eager'][] = 'cities';
        }
        if ($request->query('with_eager_children_districts')) {
            $options['eager'][] = 'districts';
        }
        if ($request->query('with_eager_parent')) {
            $options['eager'][] = 'parent';
        }

        newrelic_name_transaction(sprintf(
            'View|Load=%s',
            match (true) {
                !empty($options['lazy']) => 'Lazy{' . implode(',', $options['lazy']) . '}',
                !empty($options['eager']) => 'Eager{' . implode(',', $options['eager']) . '}',
                default => 'None',
            },
        ));
    }
}
