<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/TopicPinHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/TopicService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route topics/pin
class TopicPinHandler extends WF\DefaultPageController {
    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $messageId = (int)($params['messageId'] ?? 0);
        $service = new \Convobis\Service\TopicService();
        $service->togglePin($messageId);
        $message = (new \Convobis\Repository\MessageRepository())->findById($messageId);
        $topicId = $message ? $message->topicId : 0;
        header('Location: index.php?route=topics/view&topicId=' . $topicId);
        exit;
    }
}
