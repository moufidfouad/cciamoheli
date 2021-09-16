<?php

namespace App\Form\Field;

use EasyCorp\Bundle\EasyAdminBundle\Config\Option\TextAlign;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class VichField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null,?string $type = VichImageType::class): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplateName('crud/field/image')
            ->setFormType($type)
            ->setFormTypeOption('allow_delete',false)
            ->addCssClass('field-image')
            ->setTextAlign(TextAlign::CENTER);
    }
}