<?php

namespace App\Services;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @package App\Services
 */
class TranslatorService
{

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $key
     * @return string
     */
    public function translate(string $key): string
    {
        return $this->translator->trans($key);
    }
}