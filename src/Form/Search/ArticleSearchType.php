<?php

namespace App\Form\Search;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Entity\Search\ArticleSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query',TextType::class,[
                'required' => false,
                'label' => 'entity.article_search.fields.query.label',
                'attr' => [
                    'placeholder' => 'entity.article_search.fields.query.placeholder',
                    'class' => 'input-search-sidebar2 txt10 p-l-20 p-r-55'
                ]
            ])
            ->add('tags',EntityType::class,[
                'required' => false, 
                'label' => false,               
                'expanded' => true,
                'multiple' => true,
                'class' => Tag::class,
                'choice_label' => 'titre',
                'query_builder' => function(TagRepository $repository){
                    return $repository->findByHasArticle()->setMaxResults(10);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleSearch::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
