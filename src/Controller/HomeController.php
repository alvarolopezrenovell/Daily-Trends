<?php


namespace App\Controller;

use App\Entity\Feed;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index()
    {
        $feeds = $this->getDoctrine()->getRepository(Feed::class)->getFeedsByCurrentDate();
        return $this->render('feed/index.html.twig', ['feeds' => $feeds]);
    }

}