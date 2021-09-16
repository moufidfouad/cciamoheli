<?php

namespace App\Form\Type;

use App\Entity\Agent;
use App\Tools\Constants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class,[
                'required' => true,
                'label' => 'entity.agent.fields.username'
            ])
            ->add('nom',TextType::class,[
                'required' => true,
                'label' => 'entity.agent.fields.nom'
            ])
            ->add('prenom',TextType::class,[
                'required' => true,
                'label' => 'entity.agent.fields.prenom'
            ])
            ->add('genre',ChoiceType::class,[
                'required' => true,
                'label' => 'entity.agent.fields.genre',
                'choices' => Constants::$GENRE_CHOICES
            ])
            ->add('file',VichImageType::class,[
                'required' => false,
                'label' => 'entity.agent.fields.file',
                'attr' => [
                    'accept' => 'image/*'
                ]
            ])
            ->add('nin',TextType::class,[
                'required' => true,
                'label' => 'entity.agent.fields.nin'
            ])
            ->add('ddn',DateType::class,[
                'required' => true,
                'label' => 'entity.agent.fields.ddn',
                'widget' => 'single_text'
            ])
            ->add('ldn',TextType::class,[
                'required' => false,
                'label' => 'entity.agent.fields.ldn'
            ])
            ->add('telephone',TextType::class,[
                'required' => true,
                'label' => 'entity.agent.fields.telephone'
            ])
            ->add('published',CheckboxType::class,[
                'required' => false,
                'label' => 'entity.agent.fields.published'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
