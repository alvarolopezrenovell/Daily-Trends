<?php


namespace App\Controller;


use App\Entity\Feed;
use App\Form\FeedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("feed")
 */
class FeedController extends AbstractController
{

    /**
     * @Route("/view/{id}", name="feed_view")
     * @param Feed $feed
     * @return Response
     */
    public function view(Feed $feed)
    {
        return $this->render('feed/view.html.twig', ['feed' => $feed]);
    }

    /**
     * @Route("/create", name="feed_create")
     * @Route("/edit/{id}", name="feed_edit")
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