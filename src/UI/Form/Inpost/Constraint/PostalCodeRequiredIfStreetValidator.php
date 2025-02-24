<?php

declare(strict_types=1);

namespace App\UI\Form\Inpost\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PostalCodeRequiredIfStreetValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $object = $this->context->getRoot()->getData();
        $street = $object['street'] ?? null;

        if ($street && empty($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}