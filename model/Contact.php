<?php

namespace Convobis\Model;

class Contact {
    public $id; // Unique identifier
    public $type;
    public $value;

    public function __construct($id = null, $type = '', $value = '') {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['type'] ?? '',
            $data['value'] ?? ''
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
        ];
    }
}
