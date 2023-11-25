<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 800, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePosted = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'notes')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Tags::class, inversedBy: 'notes', cascade: ['persist'])]
    private $tag;

    public function __construct() {
        // $this->tags = new ArrayCollection();
        $this->isPublic = false;
        $this->datePosted = new \DateTime();
        $this->isActive = true;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDatePosted(): ?\DateTimeInterface
    {
        return $this->datePosted;
    }

    public function setDatePosted(\DateTimeInterface $datePosted): self
    {
        $this->datePosted = $datePosted;

        return $this;
    }

    // public function getDateRemoved(): ?\DateTimeInterface
    // {
    //     return $this->date_removed;
    // }

    // public function setDateRemoved(\DateTimeInterface $date_removed = new \DateTime()): self
    // {
    //     $this->date_removed = $date_removed;

    //     return $this;
    // }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    
    public function getTag(): ?Tags
    {
        return $this->tag;
    }

    public function setTag(Tags $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    

    // public function removeTag(Tags $tag): self
    // {
    //     if ($this->tags->removeElement($tag)) {
    //         // set the owning side to null (unless already changed)
    //         if ($tag->getNotes() === $this) {
    //             $tag->setNotes(null);
    //         }
    //     }

    //     return $this;
    // }

    // in case tags be this way ( array variable in DB )
    // public function getTags(): array
    // {
    //     $tags = $this->tags;
    //     // guarantee every user at least has ROLE_USER
    //     // $tags[] = '';

    //     return array_unique($tags);
    // }

    
}
