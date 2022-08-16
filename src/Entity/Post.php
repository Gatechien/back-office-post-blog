<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("app_api_post_browse")
     * @Groups("app_api_post")
     * @Groups("app_api_comment")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("app_api_post_browse")
     * @Groups("app_api_post")
     * @Groups("app_api_author")
     * @Groups("app_api_tag")
     * @Groups("app_api_comment")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("app_api_post_browse")
     * @Groups("app_api_post")
     */
    private $body;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("app_api_post_browse")
     * @Groups("app_api_post")
     */
    private $nbLikes;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("app_api_post_browse")
     * @Groups("app_api_post")
     * @Groups("app_api_author")
     * @Groups("app_api_tag")
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="date")
     * @Groups("app_api_post_browse")
     * @Groups("app_api_post")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("app_api_post_browse")
     * @Groups("app_api_post")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("app_api_post_browse")
     * @Groups("app_api_post")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="post", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups("app_api_post")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="posts", cascade={"persist"})
     * @Groups("app_api_post")
     */
    private $tags;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getNbLikes(): ?int
    {
        return $this->nbLikes;
    }

    public function setNbLikes(?int $nbLikes): self
    {
        $this->nbLikes = $nbLikes;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface 
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
