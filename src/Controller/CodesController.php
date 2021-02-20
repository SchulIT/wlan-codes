<?php

namespace App\Controller;

use App\Entity\WifiCode;
use App\Form\ImportCodesType;
use App\Repository\WifiCodeRepositoryInterface;
use Exception;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/admin/codes")
 */
class CodesController extends AbstractController {

    /**
     * @Route("", name="codes")
     */
    public function indexAction(Request $request, WifiCodeRepositoryInterface $codeRepository, TranslatorInterface $translator) {
        $stats = [ ];

        foreach($codeRepository->getAvailableDurations() as $duration) {
            $stats[] = [
                'duration' => $duration,
                'count' => $codeRepository->countCodes($duration),
                'available' => $codeRepository->countAvailableCodes($duration)
            ];
        }

        $form = $this->createForm(ImportCodesType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $codeRepository->beginTransaction();
                $csv = Reader::createFromFileObject($form->get('csv')->getData()->openFile());
                foreach ($csv->getRecords() as $record) {
                    // Skip comments
                    if (substr($record[0], 0, 1) === '#') {
                        continue;
                    }

                    $code = (new WifiCode())
                        ->setCode(trim($record[0]))
                        ->setDuration($form->get('duration')->getData());

                    $codeRepository->persist($code);
                }
                $codeRepository->commit();

                $this->addFlash('success', $translator->trans('codes.import.success'));
            } catch (Exception $e) {
                $this->addFlash('error', $translator->trans('codes.import.failure', [ 'message' => $e->getMessage() ]));
            }

            return $this->redirectToRoute('codes');
        }

        return $this->render('admin/codes.html.twig', [
            'form' => $form->createView(),
            'stats' => $stats
        ]);
    }
}