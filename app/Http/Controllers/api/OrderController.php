<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Orders::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            if ($orders->isEmpty()) {
                return response()->json([
                    'message' => 'No orders found'
                ], 404);
            }

            return response()->json([
                'data' => $orders,
                'message' => 'Orders retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve orders');
        }
    }

    public function show($id)
    {
        try {
            $order = $this->getOrderForCurrentUser($id);
            if (! $order) {
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'data' => $order,
                'message' => 'Order retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve order details');
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_date' => 'required|date',
                'total_price' => 'required|numeric',
                'payment' => 'required|string|max:255',
                'status' => 'required|string|max:50',
                'invoice' => 'required|string|max:255|unique:orders,invoice',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $data = $validator->validated();
            $data['user_id'] = Auth::id();

            $order = Orders::create($data);

            return response()->json([
                'data' => $order,
                'message' => 'Order created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create order');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $order = $this->getOrderForCurrentUser($id);
            if (! $order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'order_date' => 'sometimes|date',
                'total_price' => 'sometimes|numeric',
                'payment' => 'sometimes|string|max:255',
                'status' => 'sometimes|string|max:50',
                'invoice' => [
                    'sometimes',
                    'string',
                    'max:255',
                    Rule::unique('orders', 'invoice')->ignore($order->id),
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $order->fill($validator->validated());
            $order->save();

            return response()->json([
                'data' => $order,
                'message' => 'Order updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update order');
        }
    }

    public function destroy($id)
    {
        try {
            $order = $this->getOrderForCurrentUser($id);
            if (! $order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order->delete();

            return response()->json([
                'message' => 'Order deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete order');
        }
    }

    protected function getOrderForCurrentUser($id)
    {
        return Orders::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
    }
}
