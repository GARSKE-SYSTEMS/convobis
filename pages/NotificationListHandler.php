<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/NotificationListHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../util/AuthHelper.php';
require_once __DIR__ . '/../service/NotificationService.php';
require_once __DIR__ . '/../repository/UserRepository.php';

# @route notifications
class NotificationListHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        // get current user
        $email = $_SESSION['user'] ?? '';
        $userRepo = new \Convobis\Repository\UserRepository();
        $user = $userRepo->findByEmail($email);
        if (! $user) {
            return 'User not found';
        }
        $service = new \Convobis\Service\NotificationService();
        $notifications = $service->getUnread($user->id);

        $tpl = new WF\Template('notifications_list');
        $tpl->includeTemplate('head', new WF\Template('std_head'));
        $tpl->includeTemplate('js_deps', new WF\Template('js_deps'));
        $tpl->setVariable('notifications', $notifications);
        return $tpl->output();
    }
}
