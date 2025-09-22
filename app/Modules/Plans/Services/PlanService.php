<?php 
namespace App\Modules\Plans\Services;

use App\Traits\HasTranslation;
use App\Modules\Plans\Models\Plan;
use Illuminate\Support\Arr;

class PlanService
{
    use HasTranslation;

    public function getAll($active = false)
    {
        return Plan::when($active, function($query){
            return $query->where('active', true);
        })
        ->with('prices')
        ->get();
    }

    public function findById(int $id, $active = true)
    {
        return Plan::when(!$active, function($query){
            return $query->where('active', false);
        })
        ->with('prices')
        ->findOrfail($id);
    }

    // public function create($planData)
    // {
    //     $translations = Arr::pull($planData, 'translations');
    //     $plan = Plan::create($planData);
    //     $this->fillTranslations($plan, $translations);
    //     $this->attachPlanPrices($plan, $planData['pricing']);
    //     $plan->save();
    //     return $plan;
    // }

    public function update($plan, $planData)
    {
        $translations = Arr::pull($planData, 'translations');
        $plan->update($planData);
        $this->fillTranslations($plan, $translations);
        $this->attachPlanPrices($plan, $planData['pricing']);
        $plan->save();
        return $plan;
    }

    public function toggleActive($plan)
    {
        return $plan->update([
            'active' => !$plan->active
        ]);
    }

    public function delete($plan)
    {
        return $plan->delete();
    }

    private function attachPlanPrices($plan, $pricing)
    {
        if (isset($pricing) && !empty($pricing)) {
            $plan->prices()->delete();
            $plan->prices()->createMany($pricing);
        }
    }

}