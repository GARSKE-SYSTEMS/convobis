<?php

namespace Convobis\Model;

class Address {
    public $id;
    public $street;
    public $city;
    public $postalCode;
    public $country;

    public function __construct($id = null, $street = '', $city = '', $postalCode = '', $country = '') {
        $this->id = $id;
        $this->street = $street;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->country = $country;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['street'] ?? '',
            $data['city'] ?? '',
            $data['postalCode'] ?? '',
            $data['country'] ?? ''
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'street' => $this->street,
            'city' => $this->city,
            'postalCode' => $this->postalCode,
            'country' => $this->country,
        ];
    }
}
