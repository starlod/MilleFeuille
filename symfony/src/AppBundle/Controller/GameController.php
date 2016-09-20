<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Game controller.
 *
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * Typing Game
     *
     * @Route("/typing.html", name="game_typing")
     * @Method("GET")
     */
    public function typingAction()
    {
        return $this->render('game/typing.html.twig');
    }
}

