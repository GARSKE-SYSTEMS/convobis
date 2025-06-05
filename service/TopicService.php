<?php

namespace Convobis\Service;

require_once __DIR__ . '/../repository/TopicRepository.php';
require_once __DIR__ . '/../repository/MessageRepository.php';

use Convobis\Repository\TopicRepository;
use Convobis\Repository\MessageRepository;
use Convobis\Model\Topic;
use Convobis\Model\Message;

class TopicService
{
    private TopicRepository $topicRepo;
    private MessageRepository $msgRepo;

    public function __construct()
    {
        $this->topicRepo = new TopicRepository();
        $this->msgRepo = new MessageRepository();
    }

    /**
     * List topics for a client
     * @param int $clientId
     * @return Topic[]
     */
    public function listTopics(int $clientId): array
    {
        return $this->topicRepo->findAllByClient($clientId);
    }

    /**
     * Create a new topic
     * @param int $clientId
     * @param string $name
     * @param string $description
     * @return Topic
     */
    public function createTopic(int $clientId, string $name, string $description): Topic
    {
        $topic = new Topic(null, $clientId, $name, $description);
        return $this->topicRepo->save($topic);
    }

    /**
     * Archive a topic
     * @param int $topicId
     */
    public function archiveTopic(int $topicId): void
    {
        $this->topicRepo->archive($topicId);
    }

    /**
     * Add a message to a topic
     * @param int $topicId
     * @param int $authorId
     * @param string $authorType
     * @param string $content
     * @return Message
     */
    public function addMessage(int $topicId, int $authorId, string $authorType, string $content): Message
    {
        $msg = new Message(null, $topicId, $authorId, $authorType, $content);
        $saved = $this->msgRepo->save($msg);
        // dispatch event for mentions
        \Convobis\Util\EventDispatcher::dispatch('message.created', $saved);
        return $saved;
    }

    /**
     * Delete a message
     * @param int $messageId
     */
    public function deleteMessage(int $messageId): void
    {
        $this->msgRepo->delete($messageId);
    }

    /**
     * Fetch messages in a topic
     * @param int $topicId
     * @return Message[]
     */
    public function getMessages(int $topicId): array
    {
        return $this->msgRepo->findByTopic($topicId);
    }

    /**
     * Reply to a message (create new message with replyToId)
     */
    public function replyMessage(int $topicId, int $replyToId, int $authorId, string $authorType, string $content): Message
    {
        $msg = new Message(null, $topicId, $authorId, $authorType, $content);
        $saved = $this->msgRepo->save($msg);
        $saved->replyToId = $replyToId;
        $this->msgRepo->update($saved);
        return $saved;
    }

    /**
     * Toggle pin status of a message
     */
    public function togglePin(int $messageId): Message
    {
        $msg = $this->msgRepo->findById($messageId); // implement findById
        if (! $msg) {
            throw new \RuntimeException("Message not found");
        }
        $msg->isPinned = $msg->isPinned ? 0 : 1;
        $this->msgRepo->update($msg);
        return $msg;
    }
}
