<?php

namespace App\Services\RandomUserImporter;

interface CustomerRecordInterface
{
    public function updateOrCreateCustomers(array $customers): void;
}
