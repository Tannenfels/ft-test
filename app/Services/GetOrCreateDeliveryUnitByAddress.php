<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\DeliveryType;
use App\Models\DeliveryUnit;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetOrCreateDeliveryUnitByAddress
{
    public function __construct(protected string $address, protected ?int $typeId = null)
    {
    }

    public function handle()
    {
        try {
            return Delivery::query()->where('address', '=', $this->address)->firstOrFail();
        } catch (ModelNotFoundException) {
            $type = DeliveryType::query()->findOrFail($this->typeId);
            $unit = new DeliveryUnit();
            $unit->type_id = $type->id;
            $unit->address = $this->address;
            $unit->saveOrFail();

            return $unit;
        }
    }
}
