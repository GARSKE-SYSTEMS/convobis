<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientAddressDeleteHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/address/delete
class ClientAddressDeleteHandler extends WF\DefaultPageController {
    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $addressId = (int)($params['addressId'] ?? 0);
        $service = new \Convobis\Service\ClientService();
        $service->removeAddress($clientId, $addressId);
        header('Location: index.php?route=clients/detail&id=' . $clientId);
        exit;
    }
}
