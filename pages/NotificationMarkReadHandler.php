<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/NotificationMarkReadHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../util/AuthHelper.php';
require_once __DIR__ . '/../service/NotificationService.php';

# @route notifications/markread
class NotificationMarkReadHandler extends WF\DefaultPageController {
    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $id = (int)($params['id'] ?? 0);
        $service = new \Convobis\Service\NotificationService();
        $service->markRead($id);
        header('Location: index.php?route=notifications');
        exit;
    }
}
