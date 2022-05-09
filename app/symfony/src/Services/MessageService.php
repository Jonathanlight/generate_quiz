<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class MessageService
{
    public const TYPE_SUCCESS = "success";
    public const TYPE_ERROR = "error";
    public const TYPE_WARNING = "warning";

    /**
     * @var FlashBagInterface
     */
    protected $flashBag;

    /**
     * @param FlashBagInterface $flashBag
     */
    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function addSuccess(string $message)
    {
        return $this->flashBag->add(self::TYPE_SUCCESS, $message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function addError(string $message)
    {
        return $this->flashBag->add(self::TYPE_ERROR, $message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function addWarning(string $message)
    {
        return $this->flashBag->add(self::TYPE_WARNING, $message);
    }
}