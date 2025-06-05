<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientNoteDeleteHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/note/delete
class ClientNoteDeleteHandler extends WF\DefaultPageController {
    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $noteId = (int)($params['noteId'] ?? 0);
        $service = new \Convobis\Service\ClientService();
        $service->removeNote($clientId, $noteId);
        header('Location: index.php?route=clients/detail&id=' . $clientId);
        exit;
    }
}
