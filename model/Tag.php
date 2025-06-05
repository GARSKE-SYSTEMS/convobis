<?php

namespace Convobis\Model;

class Tag {
    public $id;
    public $name;

    public function __construct($id = null, $name = '') {
        $this->id = $id;
        $this->name = $name;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? ''
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
