<?php

namespace Convobis\Model;

class Attachment {
    public $id;
    public $messageId;
    public $filename;    // original filename
    public $filepath;    // stored path on server
    public $createdAt;

    public function __construct($id = null, $messageId = null, string $filename = '', string $filepath = '', $createdAt = null) {
        $this->id = $id;
        $this->messageId = $messageId;
        $this->filename = $filename;
        $this->filepath = $filepath;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['messageId'] ?? $data['message_id'] ?? null,
            $data['filename'] ?? $data['filename'] ?? '',
            $data['filepath'] ?? $data['filepath'] ?? '',
            $data['createdAt'] ?? $data['created_at'] ?? null
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'messageId' => $this->messageId,
            'filename' => $this->filename,
            'filepath' => $this->filepath,
            'createdAt' => $this->createdAt,
        ];
    }
}
