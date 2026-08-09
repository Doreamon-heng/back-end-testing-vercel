<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function getAllCustomers()
    {
        try {
            $customers = Customers::with('user')->paginate(12);

            if ($customers->isEmpty()) {
                return response()->json([
                    'message' => 'No customers found'
                ], 404);
            }

            return response()->json([
                'data' => $customers,
                'message' => 'Customers retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve customers');
        }
    }

    public function getCustomerDetails($id)
    {
        try {
            $customer = Customers::find($id);
            if (! $customer) {
                return response()->json([
                    'message' => 'Customer not found'
                ], 404);
            }

            return response()->json([
                'data' => $customer,
                'message' => 'Customer retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve customer details');
        }
    }

    public function createCustomer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:customers',
                'phone_number' => 'required|string|max:15|unique:customers',
                'address' => 'required|string|max:255',
                'bank_id' => 'required|integer|exists:banks,id',
                'account_name' => 'required|string|max:255',
                'product_id' => 'required|integer|exists:products,id',
                'category_id' => 'required|integer|exists:categories,id',
                'payment_id' => 'required|integer|exists:payments,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $customer = Customers::create($validator->validated());

            return response()->json([
                'data' => $customer,
                'message' => 'Customer created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create customer');
        }
    }

    public function updateCustomer(Request $request, $id)
    {
        try {
            $customer = Customers::find($id);
            if (! $customer) {
                return response()->json([
                    'message' => 'Customer not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
                'phone_number' => ['sometimes', 'required', 'string', 'max:15', Rule::unique('customers')->ignore($customer->id)],
                'address' => 'sometimes|required|string|max:255',
                'bank_id' => 'sometimes|required|integer|exists:banks,id',
                'account_name' => 'sometimes|required|string|max:255',
                'product_id' => 'sometimes|required|integer|exists:products,id',
                'category_id' => 'sometimes|required|integer|exists:categories,id',
                'payment_id' => 'sometimes|required|integer|exists:payments,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $customer->fill($validator->validated());
            $customer->save();

            return response()->json([
                'data' => $customer,
                'message' => 'Customer updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update customer');
        }
    }

    public function deleteCustomer($id)
    {
        try {
            $customer = Customers::find($id);
            if (! $customer) {
                return response()->json([
                    'message' => 'Customer not found'
                ], 404);
            }

            $customer->delete();
            return response()->json([
                'message' => 'Customer deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete customer');
        }
    }
}
