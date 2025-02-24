<?php

declare(strict_types=1);

namespace App\UI\Form\Inpost\Constraint;

use Symfony\Component\Validator\Constraint;

class PostalCodeRequiredIfStreet extends Constraint
{
    public $message = 'Postal code is required when the street is provided.';
}