<?php
namespace App\Modules\V1\Features\Services;

use App\Modules\V1\Features\Models\DynamicFeatures;
use App\Modules\V1\Features\Models\Feature;
use App\Modules\V1\Utilities\Services\LocalizedCache;
use App\Traits\V1\HasTranslation;
use Illuminate\Support\Arr;

class FeatureService
{
    use HasTranslation;

    private LocalizedCache $cache;
    public function __construct()
    {
        $this->cache = LocalizedCache::make(prefix: 'features', tag: 'features');
    }
    public function getAll($active = false)
    {
        return $this->cache->rememberForever(
            key: $active ? 'active' : 'all',
            callback: fn() => Feature::when($active, function($query){
                return $query->where('active', true);
            })->get()
        );
    }

    public function getDynamic()
    {
        return $this->cache->rememberForever(
            key: 'all',
            callback: fn() => DynamicFeatures::select(['name', 'quantity', 'price'])->get()
        );
    }

    public function findById(int $id, $active = true)
    {
        return Feature::when(!$active, function($query){
            return $query->where('active', false);
        })->findOrfail($id);
    }

    public function create($featureData)
    {
        $translations = Arr::pull($featureData, 'translations');
        $feature = Feature::create($featureData);
        $this->fillTranslations($feature, $translations);
        $feature->save();
        $this->flushFeaturesCache();
        return $feature;
    }

    public function update($feature, $featueData)
    {
        $translations = Arr::pull($featueData, 'translations');
        $feature->update($featueData);
        $this->fillTranslations($feature, $translations);
        $feature->save();
        $this->flushFeaturesCache();
        return $feature;
    }

    public function toggleActive($feature)
    {
        $this->flushFeaturesCache();
        return $feature->update([
            'active' => !$feature->active
        ]);
    }

    public function delete($feature)
    {
        $this->flushFeaturesCache();
        return $feature->delete();
    }

    private function flushFeaturesCache(): void
    {
        if ($this->cache->flushTag()) return;

        $this->cache->forgetAllLocales('all');
        $this->cache->forgetAllLocales('active');
        $this->cache->forgetAllLocales('dynamic');
    }

}
