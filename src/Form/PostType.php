<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'help' => 'Titre du Post'
            ])
            ->add('body', TextareaType::class,[
                'help' => 'Contenu du Post'
            ])
            ->add('publishedAt', DateTimeType::class,[
                'label' => 'Cette article a été publié le...',
                'widget' => 'single_text',
                'input' => 'datetime',
            ])
            ->add('author', null, [
                'placeholder' => 'Choisissez un auteur...',
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Choisissez les tags du Post',
                'choice_label' => 'name',
                'class' => Tag::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
