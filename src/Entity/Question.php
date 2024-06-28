<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_question_update', 'get_answer'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['get_exercise', 'get_exercise_update', 'get_question', 'get_question_create', 'get_question_update', 'get_answer'])]
    private ?int $number = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_exercise', 'get_exercise_update', 'get_question', 'get_question_create', 'get_question_update', 'get_answer'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['get_exercise', 'get_exercise_update', 'get_question', 'get_question_create', 'get_question_update','get_answer'])]
    private ?string $teacherAnswer = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, orphanRemoval: true)]
    private Collection $answers;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[Groups(['get_question_create', 'get_question_update'])]
    private ?Exercise $exercise = null;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getTeacherAnswer(): ?string
    {
        return $this->teacherAnswer;
    }

    public function setTeacherAnswer(string $teacherAnswer): static
    {
        $this->teacherAnswer = $teacherAnswer;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

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
