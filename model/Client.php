<?php

namespace Convobis\Model;

class Client {

    public $id; // Unique identifier for the client
    public $createdAt; // Timestamp of when the client was created
    public $name;
    public array $contacts; // Array of Contact objects
    public array $addresses; // Array of Address objects
    public array $notes; // Array of Note objects
    public array $tags; // Array of Tag objects
    public array $references; // Array of Reference objects
    public array $topics; // Array of Topic objects

    public function __construct($id = null, $name = '', $createdAt = null) {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
        $this->contacts = [];
        $this->addresses = [];
        $this->notes = [];
        $this->tags = [];
        $this->references = [];
        $this->topics = [];
    }

    public static function fromArray(array $data): self {
        $client = new self($data['id'] ?? null, $data['name'] ?? '', $data['createdAt'] ?? null);
        $client->contacts = array_map(fn($c) => Contact::fromArray($c), $data['contacts'] ?? []);
        $client->addresses = array_map(fn($a) => Address::fromArray($a), $data['addresses'] ?? []);
        $client->notes = array_map(fn($n) => Note::fromArray($n), $data['notes'] ?? []);
        $client->tags = array_map(fn($t) => Tag::fromArray($t), $data['tags'] ?? []);
        $client->references = array_map(fn($r) => Reference::fromArray($r), $data['references'] ?? []);
        $client->topics = array_map(fn($t) => Topic::fromArray($t), $data['topics'] ?? []);
        return $client;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'createdAt' => $this->createdAt,
            'contacts' => array_map(fn($c) => $c->toArray(), $this->contacts),
            'addresses' => array_map(fn($a) => $a->toArray(), $this->addresses),
            'notes' => array_map(fn($n) => $n->toArray(), $this->notes),
            'tags' => array_map(fn($t) => $t->toArray(), $this->tags),
            'references' => array_map(fn($r) => $r->toArray(), $this->references),
            'topics' => array_map(fn($t) => $t->toArray(), $this->topics),
        ];
    }

    public function addContact(Contact $contact): void {
        $this->contacts[] = $contact;
    }

    public function addAddress(Address $address): void {
        $this->addresses[] = $address;
    }

    public function addNote(Note $note): void {
        $this->notes[] = $note;
    }

    public function addTag(Tag $tag): void {
        $this->tags[] = $tag;
    }

    public function addReference(Reference $reference): void {
        $this->references[] = $reference;
    }

    public function addTopic(Topic $topic): void {
        $this->topics[] = $topic;
    }

    public function removeContact(Contact $contact): void {
        $this->contacts = array_filter($this->contacts, fn($c) => $c->id !== $contact->id);
    }

    public function removeAddress(Address $address): void {
        $this->addresses = array_filter($this->addresses, fn($a) => $a->id !== $address->id);
    }

    public function removeNote(Note $note): void {
        $this->notes = array_filter($this->notes, fn($n) => $n->id !== $note->id);
    }

    public function removeTag(Tag $tag): void {
        $this->tags = array_filter($this->tags, fn($t) => $t->id !== $tag->id);
    }

    public function removeReference(Reference $reference): void {
        $this->references = array_filter($this->references, fn($r) => $r->id !== $reference->id);
    }

    public function removeTopic(Topic $topic): void {
        $this->topics = array_filter($this->topics, fn($t) => $t->name !== $topic->name);
    }

    public function getContactById($id): ?Contact {
        foreach ($this->contacts as $contact) {
            if ($contact->id === $id) {
                return $contact;
            }
        }
        return null;
    }

    public function getAddressById($id): ?Address {
        foreach ($this->addresses as $address) {
            if ($address->id === $id) {
                return $address;
            }
        }
        return null;
    }

    public function getNoteById($id): ?Note {
        foreach ($this->notes as $note) {
            if ($note->id === $id) {
                return $note;
            }
        }
        return null;
    }

    public function getTagById($id): ?Tag {
        foreach ($this->tags as $tag) {
            if ($tag->id === $id) {
                return $tag;
            }
        }
        return null;
    }

    public function getReferenceById($id): ?Reference {
        foreach ($this->references as $reference) {
            if ($reference->id === $id) {
                return $reference;
            }
        }
        return null;
    }

    public function getTopicByName($name): ?Topic {
        foreach ($this->topics as $topic) {
            if ($topic->name === $name) {
                return $topic;
            }
        }
        return null;
    }



}