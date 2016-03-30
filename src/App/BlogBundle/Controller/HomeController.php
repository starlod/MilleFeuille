<?php

namespace App\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\BlogBundle\Constant;

/**
 * ホーム
 */
class HomeController extends AppController
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->redirectToRoute(Constant::HOME_URL);
    }
}
