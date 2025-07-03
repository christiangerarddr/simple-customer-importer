<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Utils\TestUtils;

class CustomerApiTest extends TestCase
{
    private TestUtils $testUtils;
    protected function setUp(): void
    {
        parent::setUp();

        $this->testUtils = new TestUtils($this->app);
    }

    public function test_customers_api_with_no_customer_record_returns_404(): void
    {
        $response = $this->get('api/customers');

        $response->assertJsonStructure([
            'success',
            'message'
        ]);

        $data = $response->json();

        $this->assertIsArray($data);
        $this->assertTrue($data['success'] == false);
        $this->assertTrue($data['message'] == 'No customers found');

        $response->assertStatus(404);
    }

    public function test_customer_by_id_api_with_no_customer_record_returns_404(): void
    {
        $response = $this->get('api/customers/1');

        $response->assertJsonStructure([
            'success',
            'message'
        ]);

        $data = $response->json();

        $this->assertIsArray($data);
        $this->assertTrue($data['success'] == false);
        $this->assertTrue($data['message'] == 'Customer does not exist');

        $response->assertStatus(404);
    }

    /**
     * A basic feature test example.
     */
    public function test_customers_api_returns_all_customers_and_correct_structure(): void
    {
        $this->testUtils->importCustomers();

        $response = $this->get('api/customers');

        $response->assertJsonStructure([
            'success',
            'customers' => [
                '*' => ['fullName', 'email', 'country']
            ]
        ]);

        $data = $response->json();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('customers', $data);
        $this->assertTrue(is_array($data['customers']));
        $this->assertNotEmpty($data['customers']);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_get_customer_by_id_api_returns_all_customers_and_correct_structure(): void
    {
        $this->testUtils->importCustomers();

        $response = $this->get('api/customers/1');

        $response->assertJsonStructure([
            'success',
            'customer' => [
                'fullName',
                'email',
                'username',
                'gender',
                'country',
                'city',
                'phone'
            ]
        ]);

        $data = $response->json();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('customer', $data);
        $this->assertTrue(is_array($data['customer']));
        $this->assertNotEmpty($data['customer']);

        $response->assertStatus(200);
    }


}
