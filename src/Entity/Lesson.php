<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *     normalizationContext={"groups"={"lesson:read","lesson:comment:read"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 *     )
 * @ORM\Entity(repositoryClass=LessonRepository::class)
 * @UniqueEntity(fields={"title"})
 * @UniqueEntity(fields={"slug"})
 */
class Lesson
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"lesson:read"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"lesson:read"})
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"lesson:read"})
     */
    private string $teacher;

    /**
     * @Groups({"lesson:read"})
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $startAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"lesson:read"})
     */
    private \DateTimeInterface $endAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="lesson", cascade={"persist", "remove"})
     * @Groups({"lesson:comment:read"})
     * @ApiSubresource
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"lesson:read"})
     */
    private string $slug;



    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->startAt = new \DateTime();
        $this->endAt = new \DateTime();

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return " " . $this->title. " ";
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTeacher(): string
    {
        return $this->teacher;
    }

    public function setTeacher(string $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getStartAt(): \DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): \DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setLesson($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getLesson() === $this) {
                $comment->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * Generates the magic method
     */

    public function __toString(){
        // to show the name of the Lesson in the select
        return $this->title;
        // to show the id of the Lesson in the select
        // return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }




}
