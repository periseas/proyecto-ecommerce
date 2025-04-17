<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->get('auth_user');

        $orders = Order::where('user_id', $user->_id ?? $user->id)->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $user = $request->get('auth_user');

        $data =[
            'user_id' => $user->_id ?? $user->id,
            'items' => $request->productos,
            'total' => $request->total,
            'date' => Carbon::now()->format("d-m-Y H:i:s"),
            'status' => "pendiente",
        ];

        $row = new Order();
        
        foreach ($data as $k => $v) {
            $row -> {$k} = $v; 
        }
        $row->save();
        
        return response()->json(['message' => 'Orden creada', 'orden' => $row], 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $order = Order::where('_id', $id)
                        ->where('user_id', $user->_id ?? $user->id)
                        ->first();

        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        return response()->json($order);
    }
    
    public function cancel(Request $request, $id)
    {
        $user = $request->get('auth_user');

        $order = Order::where('_id', $id)
                        ->where('user_id', $user->_id ?? $user->id)
                        ->first();

        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        if ($order->status === 'cancelada') {
            return response()->json(['message' => 'La orden ya está cancelada'], 400);
        }

        $order->status = 'cancelada';
        $order->save();

        return response()->json(['message' => 'Orden cancelada con éxito', 'orden' => $order]);
    }
}
