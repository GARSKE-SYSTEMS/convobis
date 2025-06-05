<?php

namespace Convobis\Model;

class Message {
    public $id;
    public $topicId;
    public $authorId;      // user or contact ID
    public $authorType;    // 'user' or 'contact'
    public $content;
    public $createdAt;
    public $replyToId; // ID of parent message for threading
    public $isPinned;  // flag for pinned messages

    public function __construct($id = null, $topicId = 0, $authorId = 0, $authorType = '', $content = '', $createdAt = null) {
        $this->id = $id;
        $this->topicId = $topicId;
        $this->authorId = $authorId;
        $this->authorType = $authorType;
        $this->content = $content;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
        $this->replyToId = null;
        $this->isPinned = 0;
    }

    public static function fromArray(array $data): self {
        $msg = new self(
            $data['id'] ?? null,
            $data['topicId'] ?? 0,
            $data['authorId'] ?? 0,
            $data['authorType'] ?? '',
            $data['content'] ?? '',
            $data['createdAt'] ?? null
        );
        $msg->replyToId = $data['replyToId'] ?? null;
        $msg->isPinned = $data['isPinned'] ?? 0;
        return $msg;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'topicId' => $this->topicId,
            'authorId' => $this->authorId,
            'authorType' => $this->authorType,
            'content' => $this->content,
            'createdAt' => $this->createdAt,
            'replyToId' => $this->replyToId,
            'isPinned' => $this->isPinned,
        ];
    }
}
