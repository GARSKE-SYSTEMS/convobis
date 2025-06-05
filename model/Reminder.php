<?php

namespace Convobis\Model;

class Reminder {
    public $id;
    public $userId;
    public $messageId;
    public $remindAt;
    public $isSent;
    public $sentAt;

    public function __construct($id = null, $userId = 0, $messageId = 0, $remindAt = '', $isSent = 0, $sentAt = null) {
        $this->id = $id;
        $this->userId = $userId;
        $this->messageId = $messageId;
        $this->remindAt = $remindAt;
        $this->isSent = $isSent;
        $this->sentAt = $sentAt;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['userId'] ?? 0,
            $data['messageId'] ?? 0,
            $data['remindAt'] ?? '',
            $data['isSent'] ?? 0,
            $data['sentAt'] ?? null
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'messageId' => $this->messageId,
            'remindAt' => $this->remindAt,
            'isSent' => $this->isSent,
            'sentAt' => $this->sentAt
        ];
    }
}
