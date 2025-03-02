<?php

namespace App\UI\Controller\Inpost;

use App\Infrastructure\Integrations\Inpost\Client\Client;
use App\Infrastructure\Integrations\Inpost\DTO\InpostParamsDataDTO;
use App\Infrastructure\Integrations\Inpost\Provider\InpostDataProvider;
use App\UI\Form\Inpost\InpostFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Infrastructure\Integrations\Inpost\DTO\InpostResponseDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            try {
                $result = $this->inpostDataProvider->getInpostData(new InpostParamsDataDTO(
                        Client::API_POINT_NAME, InpostResponseDTO::class, ['city' => $data['city']])
                );
            } catch (\Exception $e) {
                $result = null;
            }

        }

        return $this->render('Inpost/list.html.twig', [
            'form' => $form->createView(),
            'result' => $result ?? null,
        ]);
    }
}
