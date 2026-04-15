<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\DeliveryType;
use App\Models\DeliveryUnit;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetDeliveryUnit
{
    public function __construct(protected int $id)
    {
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        return Delivery::query()->findOrFail($this->id);
    }
}
