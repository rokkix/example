<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function filterByType(Request $request)
    {
        throw_unless($request->ajax(), new \Exception('Неверный тип запроса'));

        $orders = Order::where('college_id', $this->college->id)
            ->select(['id', 'number', 'date', 'type_id', 'class_id', 'status_id'])
            ->with('type:id,name', 'classType:id,name', 'status:id,name')
            ->latest();

        if ($request->has('type') && filled($request->type)) {
            $orders->where('type_id', $request->type);
        }

        $orders = $orders->paginate(config('settings.paginate.orders'));

        return response()->json([
            'view' => view('order.orders_list', compact('orders'))->render(),
            'pgn'  => (string)$orders->render()
        ]);
    }
}
