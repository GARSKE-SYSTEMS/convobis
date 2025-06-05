<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/LoginHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../service/AuthService.php';


# @route login
class LoginHandler extends WF\DefaultPageController {

    /**
     * Route for login form display and submission
     */
    public function handleGet(array $params): string {
        $tpl = new WF\Template("login");
        $tpl->includeTemplate("head", new WF\Template("std_head"));
        $tpl->includeTemplate("js_deps", new WF\Template("js_deps"));
        // Any error message
        $tpl->setVariable("error", $params['error'] ?? '');
        return $tpl->output();
    }

    public function handlePost(array $params): string {
        $email = WF\Input::sanitize('email', INPUT_TYPE_STRING, src: INPUT_SRC_POST);
        $password = WF\Input::sanitize('password', INPUT_TYPE_STRING, src: INPUT_SRC_POST);

        if( empty($email) || empty($password)) {
            // If email or password is empty, return to login with error
            return $this->handleGet(['error' => 'Email and password are required']);
        }

        $auth = new Convobis\Service\AuthService();
        if ($auth->login($email, $password)) {
            header('Location: index');
            exit;
        }
        // on failure, redisplay with error
        return $this->handleGet(['error' => 'Invalid credentials']);
    }
}
