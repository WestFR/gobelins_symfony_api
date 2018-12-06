<?php

namespace App\Services\ScoreMailer;

use App\Entity\Children;

/**
 * Class ScoreMailerMessageFactory
 * @package App\Services\ScoreMailer
 */
class ScoreMailerMessageFactory
{

    const STATE_SCORE_INCREASE = 1;
    const STATE_SCORE_FALL = -1;

    /**
     * @var array
     */
    private static $titles = [
        self::STATE_SCORE_INCREASE => '%s à gagné un niveau !',
        self::STATE_SCORE_FALL => '%s à perdu un niveau mais reste dans la course !'
    ];

    /**
     * @var array
     */
    private static $messages = [
        self::STATE_SCORE_INCREASE => 'Dites bravo à votre enfant, il vient de gagner un niveau. Le voilà maintenant au %d',
        self::STATE_SCORE_FALL => 'Attention, votre enfant à perdu un niveau, il est désormais au niveau %d !'
    ];

    /**
     * @param string $mailFrom
     * @param int $state
     * @param Children $children
     * @return \Swift_Message
     */
    public static function buildMessage(string $mailFrom, int $state, Children $children)
    {
        return (new \Swift_Message(sprintf(self::$titles[$state], $children->getFirstname())))
            ->setFrom($mailFrom)
            ->setTo($children->getParent()->getMail())
            ->setBody(sprintf(self::$messages[$state], $children->getLevel()));
    }

}