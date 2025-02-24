<?php

declare(strict_types=1);

namespace App\UI\Form\Inpost\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class CityTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($value)
    {
        return ucfirst(strtolower($value));
    }
}