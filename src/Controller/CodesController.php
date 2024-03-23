<?php

namespace App\Controller;

use App\Entity\WifiCode;
use App\Form\ImportCodesType;
use App\Import\ImportCodeRequest;
use App\Repository\WifiCodeRepositoryInterface;
use Exception;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/admin/codes')]
class CodesController extends AbstractController {

    private const BatchSize = 200;

    #[Route('/import', name: 'xhr_import', methods: [Request::METHOD_POST ])]
    public function import(#[MapRequestPayload] ImportCodeRequest $importCodeRequest, WifiCodeRepositoryInterface $codeRepository): JsonResponse {
        try {
            $codeRepository->beginTransaction();

            foreach($importCodeRequest->codes as $code) {
                if($codeRepository->codeExists($code)) {
                    continue;
                }

                $entity = (new WifiCode())
                    ->setCode($code)
                    ->setDuration($importCodeRequest->duration);

                $codeRepository->persist($entity);
            }

            $codeRepository->commit();

            return new JsonResponse(null, Response::HTTP_CREATED);
        } catch (Exception $e) {
            $codeRepository->rollback();

            return new JsonResponse([
                'type' => get_class($e),
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(path: '', name: 'codes')]
    public function index(Request $request, WifiCodeRepositoryInterface $codeRepository): Response {
        $stats = [ ];

        foreach($codeRepository->getAvailableDurations() as $duration) {
            $stats[] = [
                'duration' => $duration,
                'count' => $codeRepository->countCodes($duration),
                'available' => $codeRepository->countAvailableCodes($duration)
            ];
        }

        $form = $this->createForm(ImportCodesType::class);

        return $this->render('admin/codes.html.twig', [
            'form' => $form->createView(),
            'stats' => $stats
        ]);
    }
}