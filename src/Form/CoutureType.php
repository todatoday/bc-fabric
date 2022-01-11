<?php

namespace App\Form;

use App\Entity\Couture;
use App\form\ApplicationType;
use App\Form\ImageCoutureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CoutureType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Tapez un super titre pour votre création"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Tapez l'adresse web (automatique)", [
                'required' => false
            ]))
            ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image principale", "Donnez l'adresse d'une image qui donne vraiment en vie"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Donnez une description globale de votre création"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Tapez une description qui donne vraiment envie de voir"))
            ->add('imageCoutures', CollectionType::class, [
                'entry_type' => ImageCoutureType::class,
                'allow_add' => true,
                'allow_delete' => true
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Couture::class,
        ]);
    }
}
