<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(itemOperations={"get"},
 *     collectionOperations={"get","post"},
 *     normalizationContext={"groups"={"comment:read"}},
 *     denormalizationContext={"groups"={"comment:write"}}
 *     )
 * @ApiFilter(DateFilter::class, properties={"createdAt"})
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @Vich\Uploadable
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("comment:read")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"lesson:comment:read", "comment:read", "comment:write"})
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"lesson:comment:read", "comment:read", "comment:write"})
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"lesson:comment:read", "comment:read", "comment:write"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Lesson::class, inversedBy="comments")
     * @Groups({"comment:read", "comment:write"})
     */
    private $lesson;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"lesson:comment:read", "comment:read"})
     */
    private \DateTimeInterface $createdAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="comments", fileNameProperty="imageName", size="imageSize")
     *
     * @var File|null
     * @Assert\Image(mimeTypes="image/jpeg")
     */
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="string")
     * @var string|null
     */
    private ?string $imageName = null;

    /**
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private ?int $imageSize = null;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;


    /**
     * @ORM\Column(type="string", length=255, options={"default"="submitted"})
     *
     * @var string
     */
    private string $state = 'submitted';

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return " " . $this->author . " ";
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): self
    {
        $this->lesson = $lesson;

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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state State
     *
     * @return static
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }


}
