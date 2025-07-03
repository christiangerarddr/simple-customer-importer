<?php

namespace App\DTO;

class CustomerListDTO
{
    public string $fullName;

    public string $email;

    public string $country;

    public function __construct(string $firstName, string $lastName, string $email, string $country)
    {
        $this->fullName = $firstName.' '.$lastName;
        $this->email = $email;
        $this->country = $country;
    }

    public function toArray(): array
    {
        return [
            'fullName' => $this->fullName,
            'email' => $this->email,
            'country' => $this->country,
        ];
    }
}
