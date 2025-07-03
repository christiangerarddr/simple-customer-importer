<?php

namespace App\DTO;

class CustomerDTO
{
    public string $fullName;

    public string $email;

    public string $username;

    public string $gender;

    public string $country;

    public string $city;

    public string $phone;

    public function __construct(array $data)
    {
        $this->fullName = $data['firstName'].' '.$data['lastName'];
        $this->email = $data['email'];
        $this->username = $data['username'];
        $this->gender = $data['gender'];
        $this->country = $data['country'];
        $this->city = $data['city'];
        $this->phone = $data['phone'];
    }

    public function toArray(): array
    {
        return [
            'fullName' => $this->fullName,
            'email' => $this->email,
            'username' => $this->username,
            'gender' => $this->gender,
            'country' => $this->country,
            'city' => $this->city,
            'phone' => $this->phone,
        ];
    }
}
