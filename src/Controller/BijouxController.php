<?php

namespace App\Controller;

use App\Entity\Bijoux;
use App\Entity\CommentB;
use App\Form\BijouxType;
use App\Entity\ImageBijoux;
use App\Form\CommentBType;
use App\Repository\BijouxRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BijouxController extends AbstractController
{
    /**
     * @Route("/bijouxs", name="bijouxs_index")
     */
    public function index(BijouxRepository $repo): Response
    {
        //  On recupere le Repository de l'Entite Couture
        // $repo = $this->getDoctrine()->getRepository(Bijoux::class);

        // On recupere toute les produits de couture dans la BDD
        $bijouxs = $repo->findAll();

        return $this->render('bijoux/index.html.twig', [
            'bijouxs' => $bijouxs
        ]);
    }

    /**
     * Permet de créer un produit bijoux
     *
     * @Route("/bijouxs/new", name="bijouxs_create")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $bijoux = new Bijoux();

        // http://picsum.photos/400/400

        $form = $this->createForm(BijouxType::class, $bijoux);

        // Gere le donner poste et envoier dans le formulaire a la BDD
        $form->handleRequest($request);

        // On verifie si le form a etait sumit et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // $manager = $this->getDoctrine()->getManager();

            // Foreach sur les image 
            foreach ($bijoux->getImageBijouxs() as $imageBijoux) {
                $imageBijoux->setBijoux($bijoux);
                $manager->persist($imageBijoux);
            }

            // On ajoute l'author de produit qui l'ajouter ce produit
            $bijoux->setAuthor($this->getUser());

            $manager->persist($bijoux);
            $manager->flush();

            // Message flash pour notifier l'utilisateur
            $this->addFlash('success', "L'produit <strong>{$bijoux->getTitle()}</strong> a bien été enregistrée !");

            // On redirige ver la page qui permet de afficer une seul Produit
            return $this->redirectToRoute('bijouxs_show', [
                'slug' => $bijoux->getSlug()
            ]);
        }

        return $this->render('bijoux/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     *
     * @Route("/bijouxs/{slug}/edit", name="bijouxs_edit")
     * @Security("is_granted('ROLE_USER') and user === bijoux.getAuthor()", message="Le
     * produit ne vous appartient pas, vous ne pouvez pas le modifier !")
     * @return Response
     */
    public function edit(Bijoux $bijoux, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(BijouxType::class, $bijoux);

        // Gere le donner poste et envoier dans le formulaire a la BDD
        $form->handleRequest($request);

        // On verifie si le form a etait sumit et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // $manager = $this->getDoctrine()->getManager();

            // Foreach sur les image 
            // On passe sur chaque cutureImages
            foreach ($bijoux->getImageBijouxs() as $imageBijoux) {
                // On precise a l'image que elle appartien au produit Couture   
                $imageBijoux->setBijoux($bijoux);
                // Fait persister L'image en question 
                $manager->persist($imageBijoux);
            }
            $manager->persist($bijoux);
            $manager->flush();

            // Message flash pour notifier l'utilisateur
            $this->addFlash('success', "Les modification de produit <strong{$bijoux->getTitle()}</strong> on bien été enregistrée !");

            // On redirige ver la page qui permet de afficer une seul Produit
            return $this->redirectToRoute('bijouxs_show', [
                'slug' => $bijoux->getSlug()
            ]);
        }

        return $this->render('bijoux/edit.html.twig', [
            'form' => $form->createView(),
            'bijoux' => $bijoux
        ]);
    }

    /**
     * Permet de afficher une seule Produit
     *
     * @Route("/bijouxs/{slug}", name="bijouxs_show")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function show(Bijoux $bijoux, Request $request, EntityManagerInterface $manager)
    {
        // Je récupère l'produit qui corespond au slug !
        // $bijoux = $repo->findOneBySlug($slug);

        // On créer un nouveau commentaire 
        $commentB = new CommentB();

        // on gere et on créer le formulaire pour les commentaires
        $form = $this->createForm(CommentBType::class, $commentB);

        $form->handleRequest($request);

        // On taite le formulaire avec toute ses parametre 
        if ($form->isSubmitted() && $form->isValid()) {
            // On dit que cette commentaire et relie a un produit Couture(setCouture) et 
            // Ce produit et relier a la table de CommentaireC quilui meme et relier a 
            // l'utilisateur qui ecrit le commentaire en cette moment
            $commentB->setBijoux($commentB->getBijoux())
                ->setAuthor($this->getUser())
                ->setCreatedAt(new DateTime())
                ->setBijoux($bijoux);

            $manager->persist($commentB);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre Commentaire a bien été pris en compte !"
            );
        }

        return $this->render('bijoux/show.html.twig', [
            'bijoux' => $bijoux,
            'form'   => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un Produit
     *
     * @Route("/bijouxs/{slug}/delete", name="bijouxs_delete")
     * @Security("is_granted('ROLE_USER') and user == bijoux.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressourse")
     *
     * @param Bijoux $bijoux
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Bijoux $bijoux, EntityManagerInterface $manager)
    {
        // On supprime le produit via le manager
        $manager->remove($bijoux);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le produit <strong>{$bijoux->getTitle()}</strong> a bien été supprimée"

        );
        // on fait une redirection
        return $this->redirectToRoute("bijouxs_index");
    }
}
