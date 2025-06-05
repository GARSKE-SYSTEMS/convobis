<?php

namespace Convobis\Model;

class User {
    public int $id;
    public string $email;
    public string $passwordHash;
    public ?string $name;

    public function __construct(int $id = 0, string $email = '', string $passwordHash = '', ?string $name = null) {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->name = $name;
    }

    public static function fromArray(array $data): self {
        return new self(
            (int)($data['id'] ?? 0),
            $data['email'] ?? '',
            $data['passwordHash'] ?? $data['password_hash'] ?? '',
            $data['name'] ?? null
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password_hash' => $this->passwordHash,
            'name' => $this->name,
        ];
    }
}
