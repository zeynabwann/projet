<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour le nom
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez le nom du client',
                ],
            ])
            
            // Champ pour le prénom
            ->add('prenom', TextType::class, [
                'required' => true,
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Entrez le prénom du client',
                ],
            ])

            // Champ pour l'email
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Entrez l\'email du client',
                ],
            ])

            ->add('telephone', TelType::class, [
                'required' => true,
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone du client',
                ],
            ])
            
           

            ->add('save', SubmitType::class, [
                'label' => 'Ajouter Client',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
