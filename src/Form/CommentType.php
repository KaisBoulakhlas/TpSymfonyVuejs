<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content',TextareaType::class,array ('label' => 'Commentaire: '))
            ->add('author',TextType::class,array ('label' => 'Auteur: '))
            ->add('email',EmailType::class,array ('label' => 'Email: '))
            ->add('imageFile',VichImageType::class, array (
                'required' => true,
                'label' => 'Avatar',
                'allow_delete' => false,
                'download_link' => false,
                'image_uri' => false,
                'attr' => ['placeholder' => 'Choisir une image ...'],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
