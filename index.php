<?php

require_once __DIR__ . '/util/EventDispatcher.php';
require_once __DIR__ . '/service/NotificationService.php';


/**
 * index.php
 * 
 * VeloFrame Starting File - Code Execution begins here.
 * All Web Requests are redirected to this file using the .htaccess in project root.
 * 
 * @author Patrick Matthias Garske <patrick@garske.link>
 * @since 0.1
 */

include_once("VeloFrame/autoload.php");

use VeloFrame as WF;
use Convobis\Util\EventDispatcher;
use Convobis\Service\NotificationService;

// Subscribe notification handler to new messages
EventDispatcher::subscribe('message.created', [new NotificationService(), 'onMessageCreated']);

$server = new WF\Server();
$server->setRoutingHandler(new WF\RoutingHandler());
$server->serve();