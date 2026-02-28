<?php

namespace App\Modules\V1\Utilities\Services;

use Closure;
use Illuminate\Support\Facades\Cache;

class LocalizedCache
{
    public function __construct(
        private ?string $tag = null,     // optional cache tag group
        private ?string $prefix = null   // optional base prefix e.g. "features"
    ) {}

    public static function make(?string $prefix = null, ?string $tag = null): self
    {
        return new self(tag: $tag, prefix: $prefix);
    }

    public function get(string $key, mixed $default = null, ?string $locale = null): mixed
    {
        $fullKey = $this->key($key, $locale);

        return $this->store()
            ? Cache::tags([$this->tag])->get($fullKey, $default)
            : Cache::get($fullKey, $default);
    }

    public function rememberForever(string $key, Closure $callback, ?string $locale = null)
    {
        $fullKey = $this->key($key, $locale);

        return $this->store()
            ? Cache::tags([$this->tag])->rememberForever($fullKey, $callback)
            : Cache::rememberForever($fullKey, $callback);
    }

    public function forget(string $key, ?string $locale = null): bool
    {
        $fullKey = $this->key($key, $locale);
        return $this->store()
            ? Cache::tags([$this->tag])->forget($fullKey)
            : Cache::forget($fullKey);
    }

    public function flushTag(): bool
    {
        if (!$this->store()) {
            return false; // tags not supported or no tag provided
        }
        Cache::tags([$this->tag])->flush();
        return true;
    }

    public function forgetAllLocales(string $key): void
    {
        $locales = config('app.supported_locales', [config('app.locale', 'en')]);
        foreach ($locales as $locale) {
            $this->forget($key, $locale);
        }
    }

    private function key(string $key, ?string $locale): string
    {
        $locale = $locale ?: app()->getLocale();
        $prefix = $this->prefix ? "{$this->prefix}:" : "";
        return "{$prefix}{$key}:{$locale}";
    }

    private function store(): bool
    {
        // tags work only if tag is set AND the driver supports it (redis/memcached)
        if (!$this->tag) return false;

        $store = config('cache.default');
        return in_array($store, ['redis', 'memcached'], true);
    }
}
