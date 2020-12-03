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
        $feeds = $this->getDoctrine()->getRepository(Feed::class)->findAll();
        return $this->render('home/index.html.twig', ['feeds' => $feeds]);
    }

}