<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientsListHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

class ClientsListHandler extends WF\DefaultPageController {
    /**
     * @route clients
     */
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $clients = (new \Convobis\Service\ClientService())->findAll();

        $tpl = new WF\Template("client_list");
        $tpl->includeTemplate("head", new WF\Template("std_head"));
        $tpl->includeTemplate("js_deps", new WF\Template("js_deps"));
        $tpl->setVariable("clients", $clients);
        return $tpl->output();
    }
}
