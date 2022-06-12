<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $subject;

    #[ORM\Column(type: 'string', length: 2000)]
    private $body;

    #[ORM\ManyToOne(targetEntity: Requester::class, inversedBy: 'message')]
    #[ORM\JoinColumn(nullable: false)]
    private $requester;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isProcessed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getRequester(): ?Requester
    {
        return $this->requester;
    }

    public function setRequester(?Requester $requester): self
    {
        $this->requester = $requester;

        return $this;
    }

    public function isIsProcessed(): ?bool
    {
        return $this->isProcessed;
    }

    public function setIsProcessed(?bool $isProcessed): self
    {
        $this->isProcessed = $isProcessed;

        return $this;
    }
}
