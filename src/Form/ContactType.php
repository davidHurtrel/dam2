<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'maxLength' => 45
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'maxLength' => 45
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'maxLength' => 100
                ]
            ])
            ->add('subject', ChoiceType::class, [
                'choices' => [
                    '-- sélectionner --' => '',
                    'signaler un problème' => 'problème',
                    'postuler' => 'postuler',
                    'service après vente' => 'SAV',
                    'autre' => 'divers'
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'maxLength' => 65545
                ]
            ])
            ->add('attachment', FileType::class, [
                'required' => false,
                'help' => 'PNG, JPEG, WEBP ou PDF - 2 Mo maximum',
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximale autorisée est de {{ limit }} {{ suffix }}.',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/jp2',
                            'image/webp',
                            'application/pdf'
                        ],
                        'mimeTypesMessage' => 'Le format de fichier est invalide ({{ type }}). Les types autorisées sont : {{ types }}.'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
