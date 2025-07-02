<?php

namespace App\Services\CustomerRecord;

interface CustomerImporterInterface
{
    public function importUsers(int $results = 100, string $nationality = ''): array;
}
