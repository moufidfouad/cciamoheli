<?php

namespace App\Form\Type;

use App\Entity\NewsLetter\Subscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'label' => false,
                'attr' => [
                    'aria-label' => "Recipient's email",
                    'placeholder' => 'configuration.newsletter.form.fields.email.placeholder',
                    'class' => 'form-control p-2'
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'submit',
                'attr' => [
                    'class' => 'btn btn-secondary text-light'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscriber::class,
        ]);
    }
}