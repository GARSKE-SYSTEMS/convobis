<?php

namespace Convobis\Service;

require_once __DIR__ . '/../repository/AttachmentRepository.php';

use Convobis\Model\Attachment;
use Convobis\Repository\AttachmentRepository;

class AttachmentService
{
    private AttachmentRepository $repo;

    public function __construct()
    {
        $this->repo = new AttachmentRepository();
    }

    /**
     * Handle file upload and persist attachment record.
     */
    public function upload(int $messageId, array $file): Attachment
    {
        $uploadDir = __DIR__ . '/../uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $destPath = $uploadDir . '/' . uniqid() . '_' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $destPath);

        $att = new Attachment(null, $messageId, $file['name'], $destPath);
        return $this->repo->save($att);
    }

    public function listForMessage(int $messageId): array
    {
        return $this->repo->findByMessage($messageId);
    }
}
