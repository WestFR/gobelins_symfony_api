<?php

namespace App\Services\ScoreMailer;

use App\Entity\Children;

/**
 * Class ScoreMailerService
 * @package App\Services
 */
class ScoreMailerService
{
    const SEND_LEVEL_INTERVAL = 4;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Children
     */
    private $childrenBefore;

    /**
     * @var Children
     */
    private $childrenAfter;

    /**
     * @var string
     */
    private $mailFrom;

    /**
     * ScoreMailerService constructor.
     * @param \Swift_Mailer $mailer
     * @param string $mailFrom
     */
    public function __construct(\Swift_Mailer $mailer, string $mailFrom)
    {
        $this->mailer = $mailer;
        $this->mailFrom = $mailFrom;
    }

    /**
     * @param Children $children
     */
    public function setChildrenBeforeUpdate(Children $children)
    {
        $this->childrenBefore = clone $children;
    }

    /**
     * @param Children $children
     */
    public function setChildrenAfterUpdate(Children $children)
    {
        $this->childrenAfter = clone $children;
    }

    /**
     * Permets d'envoyer un mail au parent de l'enfant disant s'il a évolué ou au contraire regraissé de niveau
     * afin de l'avetir de son atitude en classe
     *
     * @return bool
     */
    public function checkScore(): bool
    {
        $sign = ($this->childrenAfter->getLevel() - $this->childrenBefore->getLevel()) <=> 0;

        if ($sign == 0) {
            return false;
        }

        if ($this->childrenAfter->getLevel() % self::SEND_LEVEL_INTERVAL !== 0) {
            return false;
        }

        $message = ScoreMailerMessageFactory::buildMessage($this->mailFrom, $sign, $this->childrenBefore);
        $this->mailer->send($message);
    }

}