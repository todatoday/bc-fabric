<?php

namespace App\Controller;

use App\Entity\Bijoux;
use App\Form\BijouxType;
use App\Repository\BijouxRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBijouxController extends AbstractController
{
    /**
     * @Route("/admin/bijouxs/{page<\d+>?1}", name="admin_bijouxs_index")
     * 
     */
    public function indexBijoux(BijouxRepository $repo, $page, PaginationService $pagination): Response
    {

        // $bijouxs = $pagination->getData();
        $pagination->setEntityClass(Bijoux::class)
            ->setPage($page);


        // requirements={"page": "\d+"}
        // {page<\d+>?1} le ? = optionnell et 1= valeur par default

        // Méthode find: perment de retrouver un enregistrement par son identitfiant
        // $bijoux = $repo->find(499);

        // variable de limite de page sur 10
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


        return $this->render('admin/bijoux/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition administration
     *
     * @Route("/admin/bijouxs/{id}/edit", name="admin_bijouxs_edit")
     * 
     * @param Bijoux $bijoux
     * @return void
     */
    public function edit(Bijoux $bijoux, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(BijouxType::class, $bijoux);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($bijoux);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'produit <strong>{$bijoux->getTitle()}</strong> a bien été enregistrée !"
            );
        }

        return $this->render('admin/bijoux/edit.html.twig', [
            'bijoux' => $bijoux,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de supprimer un produit
     * 
     * @Route("/admin/bijouxs/{id}/delete", name="admin_bijouxs_delete")
     *
     * @param Bijoux $bijoux
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Bijoux $bijoux, EntityManagerInterface $manager)
    {
        if (count($bijoux->getCommentBs()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'produit <strong>{$bijoux->getTitle()}</strong> 
                car ce produit possède déjà des commentaires"
            );
        } else {
            $manager->remove($bijoux);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'produit <strong>{$bijoux->getTitle()}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_bijouxs_delete');
    }
}
