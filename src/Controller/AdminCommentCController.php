<?php

namespace App\Controller;

use App\Entity\CommentC;
use App\Form\AdminCommentCoutureType;
use App\Repository\CommentCRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentCController extends AbstractController
{
    /**
     * @Route("/admin/commentcs/{page<\d+>?1}", name="admin_commentC_index")
     */
    public function index(CommentCRepository $repo, $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(CommentC::class)
            ->setPage($page);

        return $this->render('admin/comment/indexC.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * Permet de modifier un commentaires coter administration
     * 
     * @Route("/admin/commentcs/{id}/edit", name="admin_commentC_edit")
     * 
     * @return Response
     */
    public function edit(CommentC $commentC, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentCoutureType::class, $commentC);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($commentC);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire numéro {$commentC->getId()} a bien été modifié !"
            );
        }

        return $this->render('admin/comment/editCouture.html.twig', [
            'commentC' => $commentC,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/commentCs/{id}/delete", name="admin_commentC_delete")
     * 
     * @param CommentC $commentC
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(CommentC $commentC, EntityManagerInterface $manager)
    {
        $manager->remove($commentC);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de {$commentC->getAuthor()->getFullName()} a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_commentC_index');
    }
}
