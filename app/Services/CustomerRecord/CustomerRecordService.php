<?php

namespace App\Services\CustomerRecord;

use App\DTO\CustomerDTO;
use App\DTO\CustomerListDTO;
use App\Entities\Customer;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class CustomerRecordService implements CustomerRecordInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Throwable
     * @throws DoctrineDBALException
     */
    public function updateOrCreateCustomer(array $customer): void
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            $customerEntity = $this->entityManager->getRepository(Customer::class)
                ->findOneBy(['email' => $customer['email']]);

            $isNew = false;

            if (! $customerEntity) {
                $customerEntity = new Customer;
                $customerEntity->setEmail($customer['email']);
                $isNew = true;
            }

            $customerEntity->setFirstName($customer['name']['first']);
            $customerEntity->setLastName($customer['name']['last']);
            $customerEntity->setGender($customer['gender']);
            $customerEntity->setCountry($customer['location']['country']);
            $customerEntity->setCity($customer['location']['city']);
            $customerEntity->setPhone($customer['phone']);
            $customerEntity->setUsername($customer['login']['username']);
            $customerEntity->setPassword($customer['login']['password']);

            if ($isNew) {
                $this->entityManager->persist($customerEntity);
            }

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();

        } catch (Throwable $exception) {
            $this->entityManager->getConnection()->rollBack();
            logger()->error($exception);
            throw $exception;
        }

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

    /**
     * @throws Throwable
     */
    public function getCustomer(int $customerId, array $filters = []): array
    {
        try {
            $selectFields = $filters['select'] ?? [];

            $alias = 'c';

            if (empty($selectFields)) {
                $selectFields = ['c'];
            } else {
                $selectFields = array_map(fn ($field) => "$alias.".Str::camel($field), $selectFields);
            }

            $result = $this->entityManager->createQueryBuilder()
                ->select($selectFields)
                ->from(Customer::class, $alias)
                ->setParameter('id', $customerId)
                ->where($alias.'.id = :id')
                ->getQuery()
                ->getOneOrNullResult();

            if (! $result) {
                return [];
            }

            return (new CustomerDTO($result))->toArray();
        } catch (Throwable $exception) {
            logger()->error($exception);
            throw $exception;
        }
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function getCustomers(array $filters = []): array
    {
        try {
            $selectFields = $filters['select'] ?? [];

            $alias = 'c';

            if (empty($selectFields)) {
                $selectFields = ['c'];
            } else {
                $selectFields = array_map(fn ($field) => "$alias.".Str::camel($field), $selectFields);
            }

            $qb = $this->entityManager->createQueryBuilder()
                ->select($selectFields)
                ->from(Customer::class, $alias);

            $results = $qb->getQuery()->getArrayResult();

            return array_map(fn ($row) => (new CustomerListDTO(
                $row['firstName'],
                $row['lastName'],
                $row['email'],
                $row['country']
            ))->toArray(), $results);
        } catch (Throwable $exception) {
            logger()->error($exception);
            throw $exception;
        }
    }
}
