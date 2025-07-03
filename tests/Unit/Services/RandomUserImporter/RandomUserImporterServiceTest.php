<?php

namespace Tests\Unit\Services\RandomUserImporter;

use App\Services\CustomerRecord\CustomerRecordInterface;
use App\Services\RandomUserImporter\RandomUserImporterService;
use App\Utils\ApiClient;
use ReflectionClass;
use Tests\TestCase;

class RandomUserImporterServiceTest extends TestCase
{
    /**
     * Integration-like test mocking only the API client.
     *
     * @throws \Doctrine\DBAL\Exception
     * @throws \Throwable
     */
    public function test_import_users_records_to_db(): void
    {
        $mockedRandomUsersJson = __DIR__.'/../../../Data/randomUsers.json';
        $mockedRandomUsersContent = file_get_contents($mockedRandomUsersJson);
        $mockedRandomUsersResponse = json_decode($mockedRandomUsersContent, true);

        $mockedApiClient = $this->createMock(ApiClient::class);
        $mockedApiClient->method('get')->willReturnSelf();
        $mockedApiClient->method('decode')->willReturn($mockedRandomUsersResponse);

        $customerRecordService = $this->app->make(CustomerRecordInterface::class);

        $service = new RandomUserImporterService($customerRecordService);

        $reflection = new ReflectionClass($service);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setValue($service, $mockedApiClient);

        $result = $service->importUsers();

        $this->assertEquals($mockedRandomUsersResponse['results'], $result);

        $this->assertDatabaseHas('customers', [
            'email' => $mockedRandomUsersResponse['results'][0]['email'],
        ]);
    }
}
