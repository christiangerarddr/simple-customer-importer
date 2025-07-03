<?php

namespace App\Services\CustomerRecord;

use App\Entities\Customer;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class CustomerRecordService implements CustomerRecordInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateOrCreateCustomer(array $data): void
    {
        $customer = $this->entityManager->getRepository(Customer::class)
            ->findOneBy(['email' => $data['email']]);

        if (! $customer) {
            $customer = new Customer;
            $customer->setEmail($data['email']);
        }

        $customer->setFirstName($data['name']['first']);
        $customer->setLastName($data['name']['last']);
        $customer->setGender($data['gender']);
        $customer->setCountry($data['location']['country']);
        $customer->setCity($data['location']['city']);
        $customer->setPhone($data['phone']);
        $customer->setUsername($data['login']['username']);
        $customer->setPassword($data['login']['password']);

        $this->entityManager->persist($customer);
    }

    /**
     * @throws Exception
     * @throws DoctrineDBALException|Throwable
     */
    public function updateOrCreateCustomers(array $customers): void
    {

        $this->entityManager->getConnection()->beginTransaction();

        try {
            foreach ($customers as $data) {
                if (! is_array($data)) {
                    throw new Exception('Customer data must be an array');
                }

                $validator = Validator::make($data, [
                    'email' => 'required|email',
                    'name.first' => 'required|string',
                    'name.last' => 'required|string',
                    'login.username' => 'required|string',
                    'login.password' => 'required|string',
                    'location.country' => 'required|string',
                    'location.city' => 'required|string',
                    'phone' => 'required|string',
                ]);

                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                $this->updateOrCreateCustomer($data);
            }

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();

        } catch (Throwable $exception) {
            $this->entityManager->getConnection()->rollBack();
            logger()->error($exception);
            throw $exception;
        }
    }
}
