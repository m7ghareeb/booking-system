<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

function getTranslations(): array
{
    $locale = app()->getLocale();
    $cacheKey = "translations.{$locale}";

    if (app()->isProduction() && Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }

    $langPath = lang_path($locale);

    if (!is_dir($langPath)) {
        return [];
    }

    $translations = [];

    foreach (glob("{$langPath}/*.php") as $file) {
        $prefix = pathinfo($file, PATHINFO_FILENAME);
        $keys = Arr::dot(require $file);

        foreach ($keys as $key => $value) {
            $translations["{$prefix}.{$key}"] = $value;
        }
    }

    if (app()->isProduction()) {
        Cache::forever($cacheKey, $translations);
    }

    return $translations;
}
