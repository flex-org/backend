<?php 
namespace App\Modules\V1\Features\Services;

use App\Traits\V1\HasTranslation;
use App\Modules\V1\Features\Models\Feature;
use Illuminate\Support\Arr;

class FeatureService
{
    use HasTranslation;

    public function getAll($active = false)
    {
        return Feature::when($active, function($query){
            return $query->where('active', true);
        })->get();
    }

    public function findById(int $id, $active = true)
    {
        return Feature::when(!$active, function($query){
            return $query->where('active', false);
        })->findOrfail($id);
    }

    public function create($featueData)
    {
        $translations = Arr::pull($featueData, 'translations');
        $feature = Feature::create($featueData);
        $this->fillTranslations($feature, $translations);
        $feature->save();
        return $feature;
    }

    public function update($feature, $featueData)
    {
        $translations = Arr::pull($featueData, 'translations');
        $feature->update($featueData);
        $this->fillTranslations($feature, $translations);
        $feature->save();
        return $feature;
    }

    public function toggleActive($feature)
    {
        return $feature->update([
            'active' => !$feature->active
        ]);
    }

    public function delete($feature)
    {
        return $feature->delete();
    }

}