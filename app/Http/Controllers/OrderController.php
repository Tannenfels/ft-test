<?php

namespace App\Http\Controllers;

use App\Services\CreateOrderService;
use App\Services\GetDeliveryUnit;
use App\Services\GetOrCreateDeliveryUnitByAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'goods_ids' => ['required', 'array'],
            'payment_type_id' => ['int', 'required'],
            'delivery_type_id' => ['int', 'required'],
            'delivery_unit_data' => ['json', 'required'], //либо id пункта выдачи, либо id из сохранённых адресов, либо новый адрес
            'payment_data' => ['json', 'required']
        ]);

        $deliveryUnitData = json_decode($request['delivery_unit_data']);
        if (!empty($deliveryUnitData->delivery_unit_id)) {
            $deliveryUnit = (new GetDeliveryUnit($deliveryUnitData->delivery_unit_id))->handle();
        } elseif (!empty($deliveryUnitData->delivery_unit_address)) {
            $deliveryUnit = (new GetOrCreateDeliveryUnitByAddress($deliveryUnitData->delivery_unit_address, $request['delivery_type_id']));
        } else {
            abort(400);
        }

        $paymentData = json_decode($request['payment_data'], true);
        $service = new CreateOrderService($request['goods_ids'], $request['payment_type_id'], $deliveryUnit, $paymentData);
        $result = $service->handle();

        return new JsonResponse($result);
    }
}
