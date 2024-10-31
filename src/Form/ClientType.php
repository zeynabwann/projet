<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('telephone', TextType::class, [
                    'required' => true,
                    'attr' => [
                        'placeholder' => '773461882',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez renseigner un numéro de téléphone valide.',
                        ]),
                        new NotNull([
                            'message' => 'Le téléphone ne peut pas être vide',
                        ]),
                        new Regex(
                            '/^(77|78|76)([0-9]{7})$/',
                            'Le numéro de téléphone doit être au format 77XXXXXX ou 78XXXXXX ou 76XXXXXX'
                        )
                    ]
                ])
                ->add('surname', TextType::class, [
                    'required' => false,
                ])
                ->add('adresse', TextareaType::class, [
                    'required' => false,
                ])
                ->add('Save', SubmitType::class)
                ->add('photo', FileType::class, [
                    'required' => false,
                    'mapped' => false,

                ])
            ;
            
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
