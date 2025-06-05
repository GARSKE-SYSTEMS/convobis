<?php

namespace Convobis\Model;

class Note {
    public $id;
    public $content;
    public $createdAt;

    public function __construct($id = null, $content = '', $createdAt = null) {
        $this->id = $id;
        $this->content = $content;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['content'] ?? '',
            $data['createdAt'] ?? null
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'createdAt' => $this->createdAt,
        ];
    }
}
