<?php

namespace App\Services\CustomerRecord;

interface CustomerRecordInterface
{
    public function updateOrCreateCustomer(array $customer): void;

    public function updateOrCreateCustomers(array $customers): void;

    public function getCustomer(int $customerId, array $filters = []): array;

    public function getCustomers(array $filters = []): array;
}
