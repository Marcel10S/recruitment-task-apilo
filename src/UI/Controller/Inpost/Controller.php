<?php

namespace App\UI\Controller\Inpost;

use App\UI\Form\Inpost\InpostFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Infrastructure\Integrations\Inpost\Client;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Infrastructure\Integrations\Inpost\DTO\InpostResponseDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Integrations\Inpost\Provider\InpostDataProvider;

#[AsController]
#[Route(path: '/inpost', name: 'inpost_')]
class Controller extends AbstractController
{
    public function __construct(
        private readonly InpostDataProvider $inpostDataProvider,
    ) {}

    #[Route(path: '/list', name: 'list', methods: ['GET', 'POST'])]
    public function list(Request $request): Response
    {
        $postalCode = $request->request->all()['inpost_form']['postal_code'] ?? null;
        $form = $this->createForm(InpostFormType::class, null, [
            'postal_code' => $postalCode,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $result = $this->inpostDataProvider->getInpostData(Client::API_POINT_NAME, InpostResponseDTO::class, ['city' => $data['city']]);
        }

        return $this->render('Inpost/list.html.twig', [
            'form' => $form->createView(),
            'result' => $result ?? null,
        ]);
    }
}
