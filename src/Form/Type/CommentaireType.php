<?php

namespace App\Form\Type;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'attr' => [
                    'placeholder' => 'entity.commentaire.fields.name.placeholder',
                    'class' => 'form-control'
                ]
            ])
            ->add('email',EmailType::class,[
                'attr' => [
                    'placeholder' => 'entity.commentaire.fields.email.placeholder',
                    'class' => 'form-control'
                ]
            ])
            ->add('message',TextareaType::class,[
                'attr' => [
                    'placeholder' => 'entity.commentaire.fields.message.placeholder',
                    'rows' => '6',
                    'class' => 'form-control'
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'submit',
                'attr' => [
                    'class' => 'btn btn-secondary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}