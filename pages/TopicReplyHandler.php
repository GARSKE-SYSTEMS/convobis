<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/TopicReplyHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/TopicService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route topics/reply
class TopicReplyHandler extends WF\DefaultPageController {
    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $topicId = (int)($params['topicId'] ?? 0);
        $replyToId = (int)($params['replyToId'] ?? 0);
        // For reply, we need a form. Simplify: reuse content textarea prompt.
        $content = WF\Input::sanitize('content', WF\Input::INPUT_TYPE_STRING);
        $service = new \Convobis\Service\TopicService();
        $service->replyMessage($topicId, $replyToId, 0, 'user', $content);
        header('Location: index.php?route=topics/view&topicId=' . $topicId);
        exit;
    }
}
