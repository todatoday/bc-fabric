<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     *
     * @Route("/login", name="account_login")
     *
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();

        // On recupere le dernier  nom d'utilisateur qui a etait taper dans le form
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/logout",name="account_logout")
     *
     * @return void
     */
    public function logout()
    {
        // ..... rien
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //  On demande de encoder le mod de passe d'utilisateur qui et rentre dans le form
            $hash = $encoder->hashPassword($user, $user->getHash());
            // Je te moditie ton mot de passe 
            $user->setHash($hash);
            // On persiste l'utilisateur qui et entre dans le form si tout vas bien
            $manager->persist($user);
            //  Et on le en vois dans la BDD
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compre a bien été créé ! Vous pouvez maintenant vous connecter !"
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et traiter le formulaire de modification de profil
     *
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {
        // On recoupere l'utilisateur qui est acctulement connecter
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données du profil ont été enregistrée avec succès !"
            );
        }


        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder)
    {

        $passwordUpdate = new PasswordUpdate();

        // User accutuelement connecté
        $user = $this->getUser();
        // On créer le formulaire
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        // on demande la request
        $form->handleRequest($request);
        // On verifie si le formulaire et valid et si il a etait sumis
        if ($form->isSubmitted() && $form->isValid()) {
            // 1.) Verifier que le oldPassword du formulaire soit le même que le password d'user
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                // Gérer l'erreur
                // On accede au champ de form et on demande via le formulaire de recupere avec le get le mot de passe actuel et et avec le FormError on le verifie si il et bon se OK si no L"eereur est afficher
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                //  On confirme le nouveau mot de passe
                $newPassword = $passwordUpdate->getNewPassword();
                //  Change moi le mot de passe de user 
                $hash = $encoder->hashPassword($user, $newPassword);

                // On dit a user que on modifie son Hash 
                $user->setHash($hash);

                // On le persiste et on le rentre dans la BDD 
                $manager->persist($user);
                $manager->flush();

                // Message flash pour notifier les changement de mot de passe
                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );
                // On redirige ver la page d'accueil
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Perment d'afficher le profil d'utilisateur connecté
     *
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
