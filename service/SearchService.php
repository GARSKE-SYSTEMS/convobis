<?php

namespace Convobis\Service;

require_once __DIR__ . '/../repository/ClientRepository.php';
require_once __DIR__ . '/../repository/TopicRepository.php';
require_once __DIR__ . '/../repository/MessageRepository.php';

use Convobis\Repository\ClientRepository;
use Convobis\Repository\TopicRepository;
use Convobis\Repository\MessageRepository;

class SearchService
{
    private ClientRepository $clientRepo;
    private TopicRepository $topicRepo;
    private MessageRepository $msgRepo;

    public function __construct()
    {
        $this->clientRepo = new ClientRepository();
        $this->topicRepo = new TopicRepository();
        $this->msgRepo = new MessageRepository();
    }

    /**
     * Search across clients, topics, and messages by term
     * @param string $term
     * @return array{clients: array, topics: array, messages: array}
     */
    public function search(string $term): array
    {
        $clients = $this->clientRepo->findByName($term);
        $topics = $this->topicRepo->searchByNameOrDesc($term);
        $messages = $this->msgRepo->searchByContent($term);
        return ['clients' => $clients, 'topics' => $topics, 'messages' => $messages];
    }
}
