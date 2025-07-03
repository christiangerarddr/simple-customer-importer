<?php

namespace Tests\Utils;

use App\Services\CustomerRecord\CustomerRecordService;
use Doctrine\DBAL\Exception;

class TestUtils
{
    private CustomerRecordService $customerRecordService;

    public function __construct($app)
    {
        $this->customerRecordService = $app->make(CustomerRecordService::class);
    }

    /**
     * @throws \Throwable
     * @throws Exception
     */
    public function importCustomers(): void
    {
        $mockedRandomUsersJson = __DIR__.'/../Data/randomUsers.json';
        $mockedRandomUsersContent = file_get_contents($mockedRandomUsersJson);
        $mockedRandomUsersResponse = json_decode($mockedRandomUsersContent, true);

        $this->customerRecordService->updateOrCreateCustomers($mockedRandomUsersResponse['results']);
    }
}
