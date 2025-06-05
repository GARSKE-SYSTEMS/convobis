<?php

namespace Convobis\Model;

class Reference {
    public $id;
    public $url;
    public $description;

    public function __construct($id = null, $url = '', $description = '') {
        $this->id = $id;
        $this->url = $url;
        $this->description = $description;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['url'] ?? '',
            $data['description'] ?? ''
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'description' => $this->description,
        ];
    }
}
