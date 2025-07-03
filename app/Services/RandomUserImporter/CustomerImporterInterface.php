<?php

namespace App\Services\RandomUserImporter;

interface CustomerImporterInterface
{
    public function importUsers(int $results = 100, string $nationality = ''): array;
}
