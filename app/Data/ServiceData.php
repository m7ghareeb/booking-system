<?php

namespace App\Data;

use App\Models\Service;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ServiceData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $slug,
        public int $duration,
        public string $price,
    ) {}

    public static function fromModel(Service $model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->slug,
            $model->duration,
            $model->price,
        );
    }
}
