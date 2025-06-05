<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientEditHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/edit
class ClientEditHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $id = (int)($params['id'] ?? 0);
        $service = new \Convobis\Service\ClientService();
        $client = $service->findById($id);
        if (! $client) {
            return 'Client not found';
        }

        $tpl = new WF\Template("client_form");
        $tpl->includeTemplate("head", new WF\Template("std_head"));
        $tpl->includeTemplate("js_deps", new WF\Template("js_deps"));
        $tpl->setVariable("title", "Edit Client");
        $tpl->setVariable("route", "edit&id={$id}");
        $tpl->setVariable("formAction", "clients/edit&id={$id}");
        $tpl->setVariable("client", ['id' => $client->id, 'name' => $client->name, 'createdAt' => $client->createdAt]);
        return $tpl->output();
    }

    public function handlePost(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $id = (int)($params['id'] ?? 0);
        $data = ['name' => WF\Input::sanitize('name', WF\Input::INPUT_TYPE_STRING)];
        $service = new \Convobis\Service\ClientService();
        $service->update($id, $data);
        header('Location: index.php?route=clients');
        exit;
    }
}
