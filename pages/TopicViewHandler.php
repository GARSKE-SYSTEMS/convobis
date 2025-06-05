<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/TopicViewHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/TopicService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route topics/view
class TopicViewHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $topicId = (int)($params['topicId'] ?? 0);
        $service = new \Convobis\Service\TopicService();
        $topic = $service->topicRepo->findById($topicId);
        if (! $topic) {
            return 'Topic not found';
        }
        $messages = $service->getMessages($topicId);

        $tpl = new WF\Template("chat_view");
        $tpl->includeTemplate("head", new WF\Template("std_head"));
        $tpl->includeTemplate("js_deps", new WF\Template("js_deps"));
        $tpl->setVariable("topic", $topic);
        $tpl->setVariable("messages", $messages);
        return $tpl->output();
    }

    public function handlePost(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $topicId = (int)($params['topicId'] ?? 0);
        $content = WF\Input::sanitize('content', WF\Input::INPUT_TYPE_STRING);
        // authorId is null for now, authorType 'user'
        $service = new \Convobis\Service\TopicService();
        $service->addMessage($topicId, 0, 'user', $content);
        header('Location: index.php?route=topics/view&topicId=' . $topicId);
        exit;
    }
}
