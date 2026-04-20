<?php

namespace App\Data;

use App\Models\Employee;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EmployeeData extends Data
{
    /**
     * @param  DataCollection<int, ServiceData>  $services
     */
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?string $slug,
        public ?string $profile_photo_url,

        #[DataCollectionOf(ServiceData::class)]
        public ?DataCollection $services,
    ) {}

    public static function fromModel(Employee $model): self
    {
        $model->loadMissing('services');

        return new self(
            $model->id,
            $model->name,
            $model->slug,
            $model->profile_photo_url,
            ServiceData::collect($model->services, DataCollection::class),
        );
    }
}
