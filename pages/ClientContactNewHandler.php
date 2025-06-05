<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientContactNewHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/contact/new
class ClientContactNewHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $service = new \Convobis\Service\ClientService();
        $client = $service->findById($clientId);
        if (! $client) {
            return 'Client not found';
        }
        $tpl = new WF\Template('contact_form');
        $tpl->includeTemplate('head', new WF\Template('std_head')); 
        $tpl->includeTemplate('js_deps', new WF\Template('js_deps'));
        $tpl->setVariable('title', 'Add Contact for ' . $client->name);
        $tpl->setVariable('route', 'clients/contact/new');
        $tpl->setVariable('clientId', $clientId);
        $tpl->setVariable('contact', ['type' => '', 'value' => '']);
        return $tpl->output();
    }

    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $data = [
            'type' => WF\Input::sanitize('type', WF\Input::INPUT_TYPE_STRING),
            'value' => WF\Input::sanitize('value', WF\Input::INPUT_TYPE_STRING),
        ];
        $service = new \Convobis\Service\ClientService();
        $service->addContact($clientId, $data);
        header('Location: index.php?route=clients/detail&id=' . $clientId);
        exit;
    }
}
