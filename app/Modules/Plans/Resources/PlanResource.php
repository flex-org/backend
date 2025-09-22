<?php

namespace App\Modules\Plans\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'type'        => $this->type->label(),
            'description' => $this->description,
            'points'      => $this->points,
            'active'      => $this->active,
            'pricing'     => $this->whenLoaded('prices', function () {
                return $this->prices
                ->keyBy('billing_cycle')
                ->map(function ($price) {
                    return [
                        'months'     => $price->months,
                        'price'      => $price->price,
                        'discount'   => $price->discount,
                        'is_in_sale' => $price->is_in_sale,
                    ];
                });
            }),
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
