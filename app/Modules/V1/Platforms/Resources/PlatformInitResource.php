<?php

namespace App\Modules\V1\Platforms\Resources;

use App\Modules\V1\Features\Resources\FeatureResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformInitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $initData = $this['initData'];
        return [
            'features' => FeatureResource::collection(($this['features'])),
            'selected_features' => $this->when($initData->features, FeatureResource::collection($initData->features), []),
            'selling_systems' => $this['selling_systems'],
            'selected_selling_systems' => collect($initData->selling_systems ?? [])->map(fn($system) => [
                'id' => $system['id'] ?? null,
                'name' => $system['system']?->label() ?? null,
                'description' => $system['system']?->description() ?? null,
                ]) ?? [],
            'domain' => $initData->domain ?? '',
            'capacity' => (int) ($initData->capacity ?? 100),
            'storage' => (int) ($initData->storage ?? 20),
            'mobile_app' => (bool) $initData->mobile_app ?? false,
            'step' => (int) ($initData->step ?? 0),
        ];
    }
}
