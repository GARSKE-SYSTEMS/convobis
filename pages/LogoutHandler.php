<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/LogoutHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../util/AuthHelper.php';

class LogoutHandler extends WF\DefaultPageController {
    /**
     * @route logout
     */
    public function handleGet(array $params): void {
        \Convobis\Util\AuthHelper::logout();
        header('Location: index.php?route=login');
        exit;
    }
}
