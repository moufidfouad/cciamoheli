<?php

namespace App\Form\Field;

use App\Form\Type\TagType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class TagField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplateName('crud/field/text')
            ->setFormType(TagType::class)
            ->addCssClass('field-text')
            ->setCustomOption(TextField::OPTION_MAX_LENGTH, null)
            ->setCustomOption(TextField::OPTION_RENDER_AS_HTML, false);
    }
}