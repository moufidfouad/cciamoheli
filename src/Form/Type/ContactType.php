<?php

namespace App\Form\Type;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'attr' => [
                    'placeholder' => 'configuration.contact.form.fields.name.placeholder',
                    'class' => 'form-control form-control-input'
                ]
            ])
            ->add('email',EmailType::class,[
                'attr' => [
                    'placeholder' => 'configuration.contact.form.fields.email.placeholder',
                    'class' => 'form-control form-control-input'
                ]
            ])
            ->add('phone',TelType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'configuration.contact.form.fields.phone.placeholder',
                    'class' => 'form-control form-control-input'
                ]
            ])
            ->add('message',TextareaType::class,[
                'attr' => [
                    'placeholder' => 'configuration.contact.form.fields.message.placeholder',
                    'class' => 'form-control form-control-input',
                    'rows' => '6'
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
            'data_class' => Contact::class,
        ]);
    }
}