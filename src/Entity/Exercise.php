<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExerciseRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_group', 'get_group_exercise', 'get_lastid', 'get_exercise', 'get_question_create', 'get_exercise_update', 'get_question_update', 'get_user_exercise'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['get_group', 'get_group_exercise', 'get_exercise_create', 'get_exercise', 'get_exercise_update', 'get_question_update'])]
    private ?string $title = null;

    #[ORM\Column(length: 500)]
    #[Groups(['get_exercise_create', 'get_exercise', 'get_exercise_update', 'get_question_update', 'get_group_exercise'])]
    private ?string $instruction = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['get_exercise_create', 'get_exercise', 'get_exercise_update', 'get_question_update', 'get_group_exercise'])]
    private ?string $subject = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['get_group_exercise'])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\OneToMany(mappedBy: 'exercise', targetEntity: Question::class, orphanRemoval: true, cascade: ["persist"])]
    #[Groups(['get_exercise', 'get_exercise_update'])]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'exercise', targetEntity: GroupExercise::class, orphanRemoval: true)]
    #[Groups(['get_exercise'])]
    private Collection $groupExercises;

    #[ORM\OneToMany(mappedBy: 'exercise', targetEntity: UserExercise::class, orphanRemoval: true)]
    private Collection $userExercises;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->questions = new ArrayCollection();
        $this->groupExercises = new ArrayCollection();
        $this->userExercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): static
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setExercise($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getExercise() === $this) {
                $question->setExercise(null);
            }
        }

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
            $groupExercise->setExercise($this);
        }

        return $this;
    }

    public function removeGroupExercise(GroupExercise $groupExercise): static
    {
        if ($this->groupExercises->removeElement($groupExercise)) {
            // set the owning side to null (unless already changed)
            if ($groupExercise->getExercise() === $this) {
                $groupExercise->setExercise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserExercise>
     */
    public function getUserExercises(): Collection
    {
        return $this->userExercises;
    }

    public function addUserExercise(UserExercise $userExercise): static
    {
        if (!$this->userExercises->contains($userExercise)) {
            $this->userExercises->add($userExercise);
            $userExercise->setExercise($this);
        }

        return $this;
    }

    public function removeUserExercise(UserExercise $userExercise): static
    {
        if ($this->userExercises->removeElement($userExercise)) {
            // set the owning side to null (unless already changed)
            if ($userExercise->getExercise() === $this) {
                $userExercise->setExercise(null);
            }
        }

        return $this;
    }
}
