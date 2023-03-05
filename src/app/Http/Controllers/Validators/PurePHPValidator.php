<?php

declare(strict_types=1);

namespace App\Http\Controllers\Validators;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PurePHPValidator
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): array
    {
        $messages = [];

        $ltrim = fn (string $str) => ltrim($str, '.');

        // required 以外は nullable バリデーションとする
        $ruleFns = [
            'required' => static function (string $basePath, mixed $baseArray, string|int $key) use (&$messages, $ltrim): bool {
                if (empty($value = $baseArray[$key] ?? null) && $value !== '0') {
                    $messages[$ltrim("$basePath.$key")][] = "{$ltrim("$basePath.$key")} は必須です";
                    return false;
                }
                return true;
            },
            'array' => static function (string $basePath, mixed $baseArray, string|int $key) use (&$messages, $ltrim): bool {
                if ((null !== $value = $baseArray[$key] ?? null) && !is_array($value)) {
                    $messages[$ltrim("$basePath.$key")][] = "{$ltrim("$basePath.$key")} が配列ではありません";
                    return false;
                }
                return true;
            },
            'non_negative' => static function (string $basePath, mixed $baseArray, string|int $key) use (&$messages, $ltrim): bool {
                if ((null !== $value = $baseArray[$key] ?? null) && $value < 0) {
                    $messages[$ltrim("$basePath.$key")][] = "{$ltrim("$basePath.$key")} はゼロ以上の値にしてください";
                    return false;
                }
                return true;
            },
            'integer' => static function (string $basePath, mixed $baseArray, string|int $key) use (&$messages, $ltrim): bool {
                if ((null !== $value = $baseArray[$key] ?? null) && filter_var($value ?? null, FILTER_VALIDATE_INT) === false) {
                    $messages[$ltrim("$basePath.$key")][] = "{$ltrim("$basePath.$key")} は整数値にしてください";
                    return false;
                }
                return true;
            },
            'numeric' => static function (string $basePath, mixed $baseArray, string|int $key) use (&$messages, $ltrim): bool {
                if ((null !== $value = $baseArray[$key] ?? null) && !is_numeric($value ?? null)) {
                    $messages[$ltrim("$basePath.$key")][] = "{$ltrim("$basePath.$key")} は数値にしてください";
                    return false;
                }
                return true;
            },
        ];

        $ruleSets = [
            'id' => [$ruleFns['required'], $ruleFns['integer']],
            'all' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'male' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'female' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'at_2015' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'compared_to_2015' => [$ruleFns['integer']],
            'percentage_compared_to_2015' => [$ruleFns['numeric']],
            'density' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'average_age' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'median_age' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'under_14' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'under_64' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'over_65' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'percentage_under_14' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'percentage_under_64' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'percentage_over_65' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'male_under_14' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'male_under_64' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'male_over_65' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'male_percentage_under_14' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'male_percentage_under_64' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'male_percentage_over_65' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'female_under_14' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'female_under_64' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'female_over_65' => [$ruleFns['integer'], $ruleFns['non_negative']],
            'female_percentage_under_14' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'female_percentage_under_64' => [$ruleFns['numeric'], $ruleFns['non_negative']],
            'female_percentage_over_65' => [$ruleFns['numeric'], $ruleFns['non_negative']],
        ];

        $validate = function (array $input) use ($ruleFns, $ruleSets): bool {
            // 特定の階層をバリデーションする関数
            $validateLevel = function (string $basePath, array $baseArray, string|int $keyToList, array $nestedKeyToLists = []) use (&$validateLevel, $ruleFns, $ruleSets): bool {
                if (!array_key_exists($keyToList, $baseArray)) {
                    // $baseArray[$keyToList] が空であれば何もしない
                    return true;
                }
                if (!$ruleFns['array']($basePath, $baseArray, $keyToList)) {
                    // $baseArray[$keyToList] が存在する場合配列でなければならない
                    return false;
                }
                // $baseArray[$KeyToList] の要素について検証
                $valid = true;
                foreach ($baseArray[$keyToList] as $listItemIndex => $_) {
                    // $baseArray[$KeyToList][$listItemIndex] は（連想）配列でなければならない
                    if (
                        !$ruleFns['array'](
                            "$basePath.$keyToList",
                            $baseArray[$keyToList],
                            $listItemIndex,
                        )
                    ) {
                        $valid = false;
                        continue;
                    }
                    // $baseArray[$KeyToList][$listItemIndex][$fieldName] はそれぞれルールを満たしていなければならない
                    foreach ($ruleSets as $fieldName => $fns) {
                        foreach ($fns as $fn) {
                            if (!$fn(
                                "$basePath.$keyToList.$listItemIndex",
                                $baseArray[$keyToList][$listItemIndex],
                                $fieldName,
                            )) {
                                $valid = false;
                                // bail ルールの再現
                                continue 2;
                            }
                        }
                    }
                    // $baseArray[$KeyToList][$listItemIndex][$nestedNextKeyToList[*]] について再帰
                    if ($nestedKeyToLists) {
                        $valid = $validateLevel(
                                "$basePath.$keyToList.$listItemIndex",
                                $baseArray[$keyToList][$listItemIndex],
                                current($nestedKeyToLists),
                                array_slice($nestedKeyToLists, 1),
                            ) && $valid;
                    }
                }
                return $valid;
            };

            // ルート階層から 3 階層下までバリデーション
            return $validateLevel(
                '',
                $input,
                'data',
                ['cities', 'districts'],
            );
        };

        if (!$validate($input = $request->json()->all())) {
            throw ValidationException::withMessages($messages);
        }

        return $input;
    }
}
