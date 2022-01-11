<?php

namespace App\Form;

use App\Entity\CommentC;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentCType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('createdAt', DateTime::class, $this->getConfiguration("La date de commentaire", ""))
            ->add('rating', IntegerType::class, $this->getConfiguration(
                "Note sur 5",
                "Veuillez indiquer votre note de 0 à 5",
                [
                    'attr' => [
                        'min'  => 0,
                        'max'  => 5,
                        'step' => 1
                    ]
                ]
            ))
            ->add('content', TextareaType::class, $this->getConfiguration("Votre avis / témoignage", "N'hésitez pas à être très précis, cela aidera nos futurs visiteurs !"))
            // ->add('bijoux')
            // ->add('author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentC::class,
        ]);
    }
}
