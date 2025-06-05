<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientNewHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/new
class ClientNewHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();

        $tpl = new WF\Template("client_form");
        $tpl->includeTemplate("head", new WF\Template("std_head"));
        $tpl->includeTemplate("js_deps", new WF\Template("js_deps"));
        $tpl->setVariable("title", "New Client");
        $tpl->setVariable("route", "new");
        $tpl->setVariable("client", ['id' => '', 'name' => '', 'createdAt' => '']);
        return $tpl->output();
    }

    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $data = [
            'name' => WF\Input::sanitize('name', WF\Input::INPUT_TYPE_STRING)
        ];
        $service = new \Convobis\Service\ClientService();
        $service->create($data);
        header('Location: index.php?route=clients');
        exit;
    }
}
