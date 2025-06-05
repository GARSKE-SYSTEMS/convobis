<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientDeleteHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/delete
class ClientDeleteHandler extends WF\DefaultPageController {
    public function handleGet(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $id = (int)($params['id'] ?? 0);
        (new \Convobis\Service\ClientService())->delete($id);
        header('Location: index.php?route=clients');
        exit;
    }
}
