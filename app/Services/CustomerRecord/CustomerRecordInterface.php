<?php

namespace App\Services\CustomerRecord;

interface CustomerRecordInterface
{
    public function updateOrCreateCustomers(array $customers): void;
}
