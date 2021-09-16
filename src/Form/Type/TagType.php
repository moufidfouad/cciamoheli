<?php

namespace App\Form\Type;

use App\Form\DataTransformer\TagTransformer;
use App\Repository\TagRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    /**@var TagRepository */
    private $tagRepository;
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new TagTransformer($this->tagRepository,$options['finder_callback']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'finder_callback' => function(TagRepository $tagRepository, string $nom) {
                return $tagRepository->findOneBy(['nom' => $nom]);
            }
        ]);
        $resolver->setAllowedTypes('finder_callback','callable');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];
        $class = isset($attr['class']) ? $attr['class'].' ' : '';
        $class .= 'autocomplete-tag typeahead';
        $attr['class'] = $class;
        //$attr['autocomplete-url'] = $this->router->generate('admin_utility_universites');
        $view->vars['attr'] = $attr;
    }

    public function getParent()
    {
        return TextType::class;
    }
}