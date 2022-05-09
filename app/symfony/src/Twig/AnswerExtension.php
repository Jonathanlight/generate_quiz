<?php

namespace App\Twig;

use App\Entity\OptionChoice;
use App\Entity\Question;
use App\Manager\AnswerManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AnswerExtension extends AbstractExtension
{
    protected $answerManager;

    public function __construct(AnswerManager $answerManager)
    {
        $this->answerManager = $answerManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('answerByOptionChoice', [$this, 'answerByOptionChoice']),
            new TwigFunction('colorMark', [$this, 'colorMark']),
        ];
    }

    public function answerByOptionChoice(OptionChoice $optionChoice, Question $question)
    {
        return $this->answerManager->getAnswerByOptionChoice($optionChoice, $question);
    }

    public function colorMark($mark, $point): string
    {
        // 1/3
        $response = $point / 3;

        if ($mark <= $response) {
            return 'btn btn-danger';
        }

        if ($mark >= $response && $mark <= ($response * 2)) {
            return 'btn btn-warning';
        }

        if ($mark >= ($response * 2)) {
            return 'btn btn-success';
        }

        return 'btn btn-dark';
    }
}
