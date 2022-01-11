<?php

namespace App\Controller;


use App\Entity\Couture;
use App\Form\CoutureType;
use App\Service\PaginationService;
use App\Repository\CoutureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCoutureController extends AbstractController
{

    /**
     * @Route("/admin/coutures/{page<\d+>?1}", name="admin_coutures_index")
     * 
     */
    public function indexCouture(CoutureRepository $repo, $page, PaginationService $pagination): Response
    {
        // requirements={"page": "\d+"}
        // {page<\d+>?1} le ? = optionnell et 1= valeur par default

        $pagination->setEntityClass(Couture::class)
            ->setPage($page);


        // Méthode find: perment de retrouver un enregistrement par son identitfiant
        // $bijoux = $repo->find(499);

        // $limit = 10;

        // la page de depart * la limit (10) - la limit qui et de 10 produit par page
        // 1 * 10 = 10 - 10 = 0
        // 2 * 10 = 20 - 10 = 10
        // $start = $page * $limit - $limit;

        // on doit connaitre combien le total de produit pour rende la pagination dynamique
        // $total = count($repo->findAll());

        // le nombre total de page que j'ai je divise par la limit
        // 3.4 => 4 avec la fonction ciel()
        // $pages = ceil($total / $limit);


        return $this->render('admin/couture/index.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * Permet d'afficher le formulaire d'édition administration
     *
     * @Route("/admin/coutures/{id}/edit", name="admin_coutures_edit")
     * 
     * @param Couture $couture
     * @return void
     */
    public function editC(Couture $couture, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CoutureType::class, $couture);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($couture);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'produit <strong>{$couture->getTitle()}</strong> a bien été enregistrée !"
            );
        }

        return $this->render('admin/couture/edit.html.twig', [
            'couture' => $couture,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de supprimer un produit
     * 
     * @Route("/admin/coutures/{id}/delete", name="admin_coutures_delete")
     *
     * @param Couture $couture
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Couture $couture, EntityManagerInterface $manager)
    {
        if (count($couture->getCommentCs()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'produit <strong>{$couture->getTitle()}</strong> 
                 car ce produit possède déjà des commentaires"
            );
        } else {
            $manager->remove($couture);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'produit <strong>{$couture->getTitle()}</strong> a bien été supprimée !"
            );
        }
        return $this->redirectToRoute("admin_coutures_delete");
    }
}
