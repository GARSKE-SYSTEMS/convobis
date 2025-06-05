<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/TopicsListHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/TopicService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route topics
class TopicsListHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $service = new \Convobis\Service\TopicService();
        $topics = $service->listTopics($clientId);

        $tpl = new WF\Template("topic_list");
        $tpl->includeTemplate("head", new WF\Template("std_head"));
        $tpl->includeTemplate("js_deps", new WF\Template("js_deps"));
        $tpl->setVariable("topics", $topics);
        $tpl->setVariable("clientId", $clientId);
        return $tpl->output();
    }
}
