<?php

namespace App\Modules\V1\Features\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'icon' => $this->icon,
            'price' => $this->price,
            'active' => $this->active,
            'name' => $this->name,
            'description' => $this->description,
            // 'translations' => $this->when(request()->is('api/dashboard/categories/*'), fn () =>
            //     $this->translations->mapWithKeys(fn($t) => [
            //         $t->locale => [
            //             'name' => $t->name,
            //             'description' => $t->description,
            //         ],
            //     ])
            // ),
        ];
    }
}
