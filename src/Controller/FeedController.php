<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/feed")
 */
class FeedController extends AbstractController
{

    /**
     * @Route("/", name="feed_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->render('feed/index.html.twig');
    }

}