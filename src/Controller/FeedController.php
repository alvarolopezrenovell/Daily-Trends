<?php


namespace App\Controller;


use App\Entity\Feed;
use App\Form\FeedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{

    /**
     * @Route("/", name="feed_index")
     * @return Response
     */
    public function index()
    {
        return $this->render('feed/index.html.twig');
    }

    /**
     * @Route("feed/create", name="feed_create")
     * @Route("feed/edit/{id}", name="feed_edit")
     * @param Request $request
     * @param Feed|null $feed
     * @return Response
     */
    public function edit(Request $request, Feed $feed = null)
    {
        if ($feed === null) {
            $feed = new Feed();
        }

        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $em = $this->getDoctrine()->getManager();

                    $feed = $form->getData();

                    $em->persist($feed);
                    $em->flush();

                    $this->addFlash('success', 'Feed saved');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Error on save');
                }
            } else {
                $this->addFlash('danger', 'Error on save');
            }
        }

        $formView = $form->createView();

        return $this->render('feed/form.html.twig', [
          'form' => $formView,
        ]);
    }

}