<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetCustomersRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\PaginatedResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetCustomersRequest $request)
    {
        $customers = Customer::search($request->search)
            ->latest()
            ->paginate($request->limit ?? 10);

        return ApiResponse::success(new PaginatedResource($customers, CustomerResource::class), 'Customers list');
    }

    public function options(GetCustomersRequest $request)
    {
        $customers = Customer::select('id', 'name')
            ->search($request->search)
            ->orderBy('name')
            ->get();

        return ApiResponse::success(CustomerResource::collection($customers), 'Customers list');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());
        return ApiResponse::success(new CustomerResource($customer), 'Customer created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return ApiResponse::error('Customer not found', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(new CustomerResource($customer), 'Customer details');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return ApiResponse::error('Customer not found', Response::HTTP_NOT_FOUND);
        }

        $customer->update($request->validated());
        return ApiResponse::success(new CustomerResource($customer), 'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return ApiResponse::error('Customer not found', Response::HTTP_NOT_FOUND);
        }

        $customer->delete();
        return ApiResponse::success(null, 'Customer deleted successfully');
    }
}
