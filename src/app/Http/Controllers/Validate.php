<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Validators\LaravelValidator;
use App\Http\Controllers\Validators\PurePHPValidator;
use App\Http\Controllers\Validators\WildcardLessLaravelValidator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class Validate
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
            ($this->purePHPValidator)($request);
        } elseif ($request->query('with_laravel_validation')) {
            ($this->laravelValidator)($request);
        } elseif ($request->query('with_wildcard_less_laravel_validation')) {
            ($this->wildcardLessLaravelValidator)($request);
        } else {
            $request->json('data', []);
        }

        return $this->response->noContent();
    }

    private function configNewRelicTransaction(Request $request): void
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
            'Validate|Validator=%s;Target={%s}',
            match (true) {
                (bool)$request->query('with_pure_php_validation') => 'PurePHP',
                (bool)$request->query('with_laravel_validation')  => 'Laravel',
                (bool)$request->query('with_wildcard_less_laravel_validation')  => 'WildcardLessLaravel',
                default => 'None',
            },
            implode(',', $targets),
        ));
    }
}
