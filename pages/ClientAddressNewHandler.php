<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientAddressNewHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/address/new
class ClientAddressNewHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $service = new \Convobis\Service\ClientService();
        $client = $service->findById($clientId);
        if (! $client) {
            return 'Client not found';
        }
        $tpl = new WF\Template('address_form');
        $tpl->includeTemplate('head', new WF\Template('std_head'));
        $tpl->includeTemplate('js_deps', new WF\Template('js_deps'));
        $tpl->setVariable('title', 'Add Address for ' . $client->name);
        $tpl->setVariable('route', 'clients/address/new');
        $tpl->setVariable('clientId', $clientId);
        $tpl->setVariable('address', ['street' => '', 'city' => '', 'zip' => '', 'country' => '']);
        return $tpl->output();
    }

    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $data = [
            'street' => WF\Input::sanitize('street', WF\Input::INPUT_TYPE_STRING),
            'city' => WF\Input::sanitize('city', WF\Input::INPUT_TYPE_STRING),
            'zip' => WF\Input::sanitize('zip', WF\Input::INPUT_TYPE_STRING),
            'country' => WF\Input::sanitize('country', WF\Input::INPUT_TYPE_STRING),
        ];
        $service = new \Convobis\Service\ClientService();
        $service->addAddress($clientId, $data);
        header('Location: index.php?route=clients/detail&id=' . $clientId);
        exit;
    }
}
