<?php

namespace App\Controller;


use App\Entity\CommentB;
use App\Form\AdminCommentBijouxType;
use App\Repository\CommentBRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentBController extends AbstractController
{
    /**
     * @Route("/admin/commentbs/{page<\d+>?1}", name="admin_commentB_index")
     */
    public function index(CommentBRepository $repo, $page, PaginationService $pagination): Response
    {
        // $repo = $this->getDoctrine()->getRepository(CommentB::class);

        $pagination->setEntityClass(CommentB::class)
            ->setLimit(5)
            ->setPage($page);

        return $this->render('admin/comment/indexB.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * Permet de modifier un commentaires coter administration
     * 
     * @Route("/admin/commentbs/{id}/edit", name="admin_commentB_edit")
     * 
     * @return Response
     */
    public function edit(CommentB $commentB, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentBijouxType::class, $commentB);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($commentB);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire numéro {$commentB->getId()} a bien été modifié !"
            );
        }

        return $this->render('admin/comment/editBijoux.html.twig', [
            'commentB' => $commentB,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/commentbs/{id}/delete", name="admin_commentB_delete")
     * 
     * @param CommentB $commentB
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(CommentB $commentB, EntityManagerInterface $manager)
    {
        $manager->remove($commentB);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de {$commentB->getAuthor()->getFullName()} a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_commentB_index');
    }
}
