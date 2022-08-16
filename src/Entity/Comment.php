<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("app_api_comment_browse")
     * @Groups("app_api_comment")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups("app_api_comment_browse")
     * @Groups("app_api_comment")
     */
    private $username;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups("app_api_comment_browse")
     * @Groups("app_api_comment")
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("app_api_comment_browse")
     * @Groups("app_api_comment")
     */
    private $body;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups("app_api_comment_browse")
     * @Groups("app_api_comment")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups("app_api_comment_browse")
     * @Groups("app_api_comment")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups("app_api_comment")
     */
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
