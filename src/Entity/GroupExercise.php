<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupExerciseRepository::class)]
#[Groups(['get_group_exercise'])]
class GroupExercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_group', 'get_exercise'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['get_group', 'get_exercise'])]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'groupExercises')]
    #[Groups(['get_group'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercise $exercise = null;

    #[ORM\ManyToOne(inversedBy: 'groupExercises')]
    #[Groups(['get_exercise'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group = null;

    public function __construct()
    {
        $this->status = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

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

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): static
    {
        $this->group = $group;

        return $this;
    }
}
