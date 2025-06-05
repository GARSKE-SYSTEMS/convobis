<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientContactDeleteHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/contact/delete
class ClientContactDeleteHandler extends WF\DefaultPageController {
    public function handlePost(array $params) {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $contactId = (int)($params['contactId'] ?? 0);
        $service = new \Convobis\Service\ClientService();
        $service->removeContact($clientId, $contactId);
        header('Location: index.php?route=clients/detail&id=' . $clientId);
        return;
    }
}
