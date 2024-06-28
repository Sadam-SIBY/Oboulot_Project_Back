<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserExerciseRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserExerciseRepository::class)]
class UserExercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['get_user_exercise'])]
    private ?bool $isDone = null;

    #[ORM\ManyToOne(inversedBy: 'userExercises')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_user_exercise'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userExercises')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_user_exercise'])]
    private ?Exercise $exercise = null;

    public function __construct()
    {
        $this->isDone = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): static
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): static
    {
        $this->exercise = $exercise;

        return $this;
    }
}
