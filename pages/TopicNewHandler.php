<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/TopicNewHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/TopicService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route topics/new
class TopicNewHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $tpl = new WF\Template("topic_form");
        $tpl->includeTemplate("head", new WF\Template("std_head"));
        $tpl->includeTemplate("js_deps", new WF\Template("js_deps"));
        $tpl->setVariable("title", "New Topic");
        $tpl->setVariable("route", "new&clientId={$clientId}");
        $tpl->setVariable("clientId", $clientId);
        $tpl->setVariable("topic", ['id' => '', 'name' => '', 'description' => '']);
        return $tpl->output();
    }

    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $name = WF\Input::sanitize('name', WF\Input::INPUT_TYPE_STRING);
        $description = WF\Input::sanitize('description', WF\Input::INPUT_TYPE_STRING);
        $service = new \Convobis\Service\TopicService();
        $service->createTopic($clientId, $name, $description);
        header('Location: index.php?route=topics&clientId=' . $clientId);
        exit;
    }
}
