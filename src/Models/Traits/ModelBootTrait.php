<?php

namespace Dainsys\Timy\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

trait ModelBootTrait
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            static::removeCacheKey();
        });

        static::deleting(function () {
            static::removeCacheKey();
        });

        // static::restoring(function () {
        //     static::removeCacheKey();
        // });
    }

    protected static function removeCacheKey()
    {
        $repository_keys = [
            Str::of(class_basename(self::class))->snake()->lower()->plural()->__toString(),
            'timy.dispositions',
            'timy.teams',
            'timy.timers',
            'timy.roles',
            'timy.usersWithoutRole',
        ];

        foreach ($repository_keys as $value) {
            Cache::forget($value);
        }
        // Cache::flush();
    }
}
