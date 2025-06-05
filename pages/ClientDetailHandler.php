<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientDetailHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/detail
class ClientDetailHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $id = (int)($params['id'] ?? 0);
        $service = new \Convobis\Service\ClientService();
        $client = $service->findById($id);
        if (! $client) {
            return 'Client not found';
        }
        $tpl = new WF\Template("client_detail");
        $tpl->includeTemplate("head", new WF\Template("std_head"));
        $tpl->includeTemplate("js_deps", new WF\Template("js_deps"));
        $tpl->setVariable("client", $client);
        return $tpl->output();
    }
}
