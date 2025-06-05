<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/ClientNoteNewHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/ClientService.php';
require_once __DIR__ . '/../util/AuthHelper.php';

# @route clients/note/new
class ClientNoteNewHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $service = new \Convobis\Service\ClientService();
        $client = $service->findById($clientId);
        if (! $client) {
            return 'Client not found';
        }
        $tpl = new WF\Template('note_form');
        $tpl->includeTemplate('head', new WF\Template('std_head'));
        $tpl->includeTemplate('js_deps', new WF\Template('js_deps'));
        $tpl->setVariable('title', 'Add Note for ' . $client->name);
        $tpl->setVariable('route', 'clients/note/new');
        $tpl->setVariable('clientId', $clientId);
        $tpl->setVariable('note', ['content' => '']);
        return $tpl->output();
    }

    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $clientId = (int)($params['clientId'] ?? 0);
        $data = [
            'content' => WF\Input::sanitize('content', WF\Input::INPUT_TYPE_STRING),
            'createdAt' => date('Y-m-d H:i:s'),
        ];
        $service = new \Convobis\Service\ClientService();
        $service->addNote($clientId, $data);
        header('Location: index.php?route=clients/detail&id=' . $clientId);
        exit;
    }
}
