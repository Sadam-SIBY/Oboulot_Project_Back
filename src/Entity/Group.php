<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
#[Groups(['get_group'])]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_exercise', 'get_user', 'get_group_exercise'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['get_exercise', 'get_user', 'get_group_exercise'])]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $level = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groups')]
    private Collection $user;

    #[ORM\OneToMany(mappedBy: 'group', targetEntity: GroupExercise::class, orphanRemoval: true)]
    private Collection $groupExercises;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->groupExercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            // $user->addGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, GroupExercise>
     */
    public function getGroupExercises(): Collection
    {
        return $this->groupExercises;
    }

    public function addGroupExercise(GroupExercise $groupExercise): static
    {
        if (!$this->groupExercises->contains($groupExercise)) {
            $this->groupExercises->add($groupExercise);
            $groupExercise->setGroup($this);
        }

        return $this;
    }

    public function removeGroupExercise(GroupExercise $groupExercise): static
    {
        if ($this->groupExercises->removeElement($groupExercise)) {
            // set the owning side to null (unless already changed)
            if ($groupExercise->getGroup() === $this) {
                $groupExercise->setGroup(null);
            }
        }

        return $this;
    }

}
