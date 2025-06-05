<?php

namespace Convobis\Model;

class Notification {
    public $id;
    public $userId;
    public $type;
    public $referenceId;
    public $messageId;
    public $isRead;
    public $createdAt;

    public function __construct(
        $id = null,
        $userId = 0,
        $type = '',
        $referenceId = null,
        $messageId = null,
        $isRead = 0,
        $createdAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->type = $type;
        $this->referenceId = $referenceId;
        $this->messageId = $messageId;
        $this->isRead = $isRead;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['userId'] ?? 0,
            $data['type'] ?? '',
            $data['referenceId'] ?? null,
            $data['messageId'] ?? null,
            $data['isRead'] ?? 0,
            $data['createdAt'] ?? null
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'type' => $this->type,
            'referenceId' => $this->referenceId,
            'messageId' => $this->messageId,
            'isRead' => $this->isRead,
            'createdAt' => $this->createdAt,
        ];
    }
}
