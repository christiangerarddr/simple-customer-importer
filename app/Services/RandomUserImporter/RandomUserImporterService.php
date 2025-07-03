<?php

namespace App\Services\RandomUserImporter;

use App\Services\CustomerRecord\CustomerRecordInterface;
use App\Utils\ApiClient;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Throwable;

class RandomUserImporterService implements CustomerImporterInterface
{
    private ApiClient $client;

    public function __construct(protected CustomerRecordInterface $record)
    {
        $baseUri = config('user_source.base_uri');
        $this->client = new ApiClient($baseUri);
    }

    /**
     * @throws Throwable
     * @throws DoctrineDBALException
     */
    public function importUsers(int $results = 100, string $nationality = 'AU'): array
    {
        try {
            $query = ['query' => ['results' => $results, 'nat' => $nationality]];

            $importedUsers = $this->client->get($query)->decode();

            $this->record->updateOrCreateCustomers($importedUsers['results']);

            return $importedUsers['results'];
        } catch (Throwable $exception) {
            logger()->error($exception);
            throw $exception;
        }
    }
}
