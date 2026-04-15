<?php

namespace App\Services;

use App\Models\DeliveryUnit;
use App\Models\Good;
use App\Models\Order;
use App\Models\OrderGood;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CreateOrderService
{
    protected int $userId;
    protected Order $order;
    public function __construct(protected array $goodsIds, protected int $paymentTypeId, protected DeliveryUnit $unit, protected array $paymentData)
    {
        $this->userId = auth()->id();
    }

    public function handle(): Order
    {
        DB::transaction(function () {
            $order = new Order();
            $order->user_id = auth()->id();
            $goods = Good::query()->whereIn('id', $this->goodsIds)->get();

            if ($goods->isEmpty()) {
                throw new ModelNotFoundException();
            }

            foreach ($goods as $good) {
                $orderGood = new OrderGood();
                $orderGood->order_id = $order->id;
                $orderGood->good_id = $good->id;
                $orderGood->saveOrFail();
            }
            //Далее создание сущностей платежей, доставки итд.
            $this->order = $order;
        });

        return $this->order;
    }
}
