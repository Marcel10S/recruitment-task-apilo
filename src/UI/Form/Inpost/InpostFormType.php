<?php

declare(strict_types=1);

namespace App\UI\Form\Inpost;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Regex;
use App\UI\Form\Inpost\Transformer\CityTransformer;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\UI\Form\Inpost\Constraint\PostalCodeRequiredIfStreet;

class InpostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length(['min' => 3, 'max' => 64]),
                ],
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 64]),
                ],
            ])
            ->add('postal_code', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Regex(['pattern' => '/^[0-9]{2}-[0-9]{3}$/', 'message' => 'Invalid postal code format.']),
                    new PostalCodeRequiredIfStreet(),
                ],
            ]);

        $builder->get('city')->addModelTransformer(new CityTransformer());

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (isset($data['postal_code']) && $data['postal_code'] === '01-234') {
                $form->add('name', TextType::class, [
                    'required' => false,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'allow_extra_fields' => true,
        ]);
    }
}