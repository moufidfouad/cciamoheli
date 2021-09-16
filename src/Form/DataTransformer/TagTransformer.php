<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

class TagTransformer implements DataTransformerInterface
{
    /**@var TagRepository */
    private $tagRepository;
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function transform($value)
    {
        return implode('; ',$value->toArray());
    }

    public function reverseTransform($value)
    {
        $titres = array_unique(array_map(function($str){
            return $str;
        },array_filter(array_map('trim',explode(';',$value)))));

        $tags = $this->tagRepository->findBy([
            'titre' => $titres
        ]);

        $newTitres = array_diff($titres,$tags);

        foreach($newTitres as $titre){
            $tag = new Tag();
            $tag->setTitre($titre);
            $tags[] = $tag;
        }
        return $tags;
    }
}