<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Bijoux;
use App\Entity\Couture;
use App\Entity\CommentB;
use App\Entity\CommentC;
use App\Entity\ImageBijoux;
use App\Entity\ImageCouture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        // $slugify = new Slugify();

        // Je créer un nouveau son Role qui est le Role Admin
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        // Je créer un Utilisateur qui aurra le Role Admin
        $adminUser = new User();
        $adminUser->setFirstName('Gheorghina')
            ->setLastName('Costincianu')
            ->setEmail('gheorghina@gmail.com')
            ->setHash($this->encoder->hashPassword($adminUser, 'password'))
            ->setPicture('https://lorempixel.com/64/64/')
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('<p></p>', $faker->paragraphs(3)) . '</p>')
            ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // Nous gérons les utilisateurs 
        //  On créer un tableaux vide des utilisateurs
        $users = [];
        // Un tableaux pour le genres (fille ou garson)
        $genres = ['male', 'female'];

        for ($i = 1; $i <= 10; $i++) {
            //  On créer un User 
            $user = new User();
            // on vas choisire le genre qui il est au azzard de ce tableaux
            $genre = $faker->randomElement($genres);

            // Lien de l'api randomuser.me
            $picture = 'https://randomuser.me/api/portraits/';
            //  On demande a Faker de calculer une image entre 1 et 99 et on concatenate le jpg
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';


            // CONDITION TERNAIRE POUR GENRE PICTURE
            //  ON dit Si l'image et un homme je veut ajouter l'id de la photo
            //  si non si se femme je veus ajouter son Id de la photo
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;


            // Hashage de mot de passe qui implemente la (UserPasswordHasherInterface)
            $hash = $this->encoder->hashPassword(
                $user,
                'password'
            );

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('<p></p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

            // On persiste l'utilisateur
            $manager->persist($user);

            // Et on le ajoute dans le tableaux des Utilisateurs que on a créer vide
            $users[] = $user;
        }

        // Nous gérons les produit 
        // BIJOUX //
        for ($j = 1; $j <= 9; $j++) {
            $bijoux = new Bijoux();

            $title = $faker->sentence();
            // $slug = $slugify->slugify($title);
            $coverImage = $faker->imageUrl(1000, 450);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';

            //  On créer une variable user dans le tableaux des users
            // et on lui demande compter et de trouver un user au azzard dans le tableaux des users
            $user = $users[mt_rand(0, count($users) - 1)];

            $bijoux->setTitle($title)
                // ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setAuthor($user);

            // Image de Bijoux
            for ($j = 1; $j <= mt_rand(2, 9); $j++) {
                $imageBijoux = new ImageBijoux();

                $imageBijoux->setUrl($faker->imageUrl($width = 640, $height = 480, 'cats', true, true, 'Faker'))
                    ->setCaption($faker->sentence())
                    ->setBijoux($bijoux);

                $manager->persist($imageBijoux);
            }

            //  Gestion de commentaires 
            if (mt_rand(0, 1)) {
                $commentB = new CommentB();
                $commentB->setContent($faker->paragraph())
                    ->setRating(mt_rand(1, 5))
                    ->setAuthor($user)
                    ->setBijoux($bijoux);

                $manager->persist($commentB);
            }

            $manager->persist($bijoux);
        }

        // PRODUIT DE COUTURE //
        for ($i = 1; $i <= 9; $i++) {
            $couture = new Couture();

            $title = $faker->sentence();
            // $slug = $slugify->slugify($title);
            $coverImage = $faker->imageUrl(1000, 450);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';

            $couture->setTitle($title)
                // ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setAuthor($user);

            //  Image Couture
            for ($i = 1; $i <= mt_rand(2, 9); $i++) {
                $imageCouture = new ImageCouture();

                $imageCouture->setUrl($faker->imageUrl($width = 640, $height = 480, 'cats', true, true, 'Faker'))
                    ->setCaption($faker->sentence())
                    ->setCouture($couture);

                $manager->persist($imageCouture);
            }

            //  Gestion de commentaires 
            if (mt_rand(0, 1)) {
                $commentC = new CommentC();
                $commentC->setContent($faker->paragraph())
                    ->setRating(mt_rand(1, 5))
                    ->setAuthor($user)
                    ->setCouture($couture);

                $manager->persist($commentC);
            }



            $manager->persist($couture);
        }

        $manager->flush();
    }
}
