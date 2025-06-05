<?php

namespace Convobis\Model;

class Topic {
    public $id;
    public $clientId;
    public string $name;
    public string $description;
    public $createdAt;
    public bool $isArchived;
    public array $messages; // Array of Message objects

    public function __construct($id = null, $clientId = null, string $name = '', string $description = '', $createdAt = null, bool $isArchived = false) {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
        $this->isArchived = $isArchived;
        $this->messages = [];
    }

    public static function fromArray(array $data): self {
        $topic = new self(
            $data['id'] ?? null,
            $data['clientId'] ?? null,
            $data['name'] ?? '',
            $data['description'] ?? '',
            $data['createdAt'] ?? null,
            (bool)($data['isArchived'] ?? false)
        );
        $topic->messages = array_map(fn($m) => Message::fromArray($m), $data['messages'] ?? []);
        return $topic;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'clientId' => $this->clientId,
            'name' => $this->name,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
            'isArchived' => $this->isArchived,
            'messages' => array_map(fn($m) => $m->toArray(), $this->messages),
        ];
    }

    
}