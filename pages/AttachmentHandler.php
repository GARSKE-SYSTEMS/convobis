<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/AttachmentHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/AttachmentService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route attachment/upload
class AttachmentUploadHandler extends WF\DefaultPageController {
    public function handlePost(array $params) {
        \Convobis\Util\AuthHelper::requireAuth();
        $messageId = (int)($params['messageId'] ?? 0);
        $file = $_FILES['file'] ?? null;
        if (!$file) {
            return 'No file uploaded';
        }
        $service = new Convobis\Service\AttachmentService();
        $att = $service->upload($messageId, $file);
        header('Location: index.php?route=topics/view&topicId=' . $params['topicId']);
        exit;
    }
}
