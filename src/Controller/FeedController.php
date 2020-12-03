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

                    return $this->redirectToRoute('feed_view', ['id' => $feed->getId()]);
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

    /**
     * @Route("/delete/{id}", name="feed_delete")
     * @param Feed $feed
     * @return Response
     */
    public function delete(Feed $feed)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($feed);
            $em->flush();

            $this->addFlash('success', 'Feed removed');
            return $this->redirectToRoute('home');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Error on delete');
        }

        return $this->redirectToRoute('feed_view', ['id' => $feed->getId()]);
    }

}