<?php

namespace App\UI\Controller\Inpost;

use App\UI\Form\Inpost\InpostFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Integrations\Inpost\Provider\InpostDataProvider;

#[AsController]
#[Route(path: '/inpost', name: 'inpost_')]
class Controller extends AbstractController
{
    const API_POINT_NAME = "points";

    public function __construct(
        private readonly InpostDataProvider $inpostDataProvider,
    ) {}

    #[Route(path: '/list', name: 'list', methods: ['GET', 'POST'])]
    public function list(Request $request): Response
    {
        $form = $this->createForm(InpostFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $result = $this->inpostDataProvider->getInpostData(static::API_POINT_NAME, $data['city']);
        }

        return $this->render('Inpost/list.html.twig', [
            'form' => $form->createView(),
            'result' => $result ?? null,
        ]);
    }
}
