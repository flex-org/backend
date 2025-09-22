<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasTranslation
{
    private function fillTranslations(Model $model, array $translations): void
    {
        foreach ($translations as $locale => $fields) {
            $model->translateOrNew($locale)->fill($fields);
        }
    }

}
