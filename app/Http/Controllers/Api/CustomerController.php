<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomerRecord\CustomerRecordInterface;
use Throwable;

class CustomerController extends Controller
{
    public function __construct(protected CustomerRecordInterface $customerRecord) {}

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        try {
            $filters = [
                'select' => [
                    'first_name',
                    'last_name',
                    'country',
                    'email',
                ],
            ];

            $customers = $this->customerRecord->getCustomers($filters);

            return response()->json([
                'success' => true,
                'customers' => $customers,
            ]);

        } catch (Throwable $exception) {

            logger()->error($exception);

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $customerId)
    {
        try {
            $filters = [
                'select' => [
                    'first_name',
                    'last_name',
                    'email',
                    'username',
                    'gender',
                    'country',
                    'city',
                    'phone',
                ],
            ];

            $customer = $this->customerRecord->getCustomer($customerId, $filters);

            return response()->json([
                'success' => true,
                'customer' => $customer,
            ]);

        } catch (Throwable $exception) {

            logger()->error($exception);

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
