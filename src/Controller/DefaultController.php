<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\WifiCode;
use App\Form\RequestWifiCodeType;
use App\Repository\WifiCodeRepositoryInterface;
use App\Security\Voter\CodeVoter;
use App\Settings\ApplicationSettings;
use App\WifiCodes\CodeManager;
use App\WifiCodes\NoCodeAvailableException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {

    private const ItemsPerPage = 25;

    /**
     * @Route("/", name="dashboard")
     * @Route("/start")
     */
    public function indexAction(WifiCodeRepositoryInterface $repository, CodeManager $codeManager, Request $request) {
        /** @var User $user */
        $user = $this->getUser();
        $page = $request->query->getInt('page', 1);
        $pages = ceil((float)$repository->countAllByUser($user) / static::ItemsPerPage);

        if($page <= 0 || $page > $pages) {
            $page = 1;
        }

        $codes = $repository->findAllByUser($user, ($page - 1) * static::ItemsPerPage, static::ItemsPerPage);

        $form = $this->createForm(RequestWifiCodeType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $code = $codeManager->requestCode($form->get('duration')->getData(), $form->get('comment')->getData());

                $this->addFlash('success', 'request.success');

                return $this->redirectToRoute('show_code', [
                    'uuid' => $code->getUuid()
                ]);
            } catch (NoCodeAvailableException $e) {
                $this->addFlash('error', 'request.error.no_codes');
            } catch (Exception $e) {
                $this->addFlash('error', 'request.error.generic');
            }
        }

        return $this->render('index.html.twig', [
            'page' => $page,
            'pages' => $pages,
            'codes' => $codes,
            'form' => $form->createView(),
            'almost_all_used' => $codeManager->areAllCodesAlmostUsed()
        ]);
    }

    /**
     * @Route("/code/{uuid}", name="show_code")
     */
    public function showAction(WifiCode $code, ApplicationSettings $settings) {
        $this->denyAccessUnlessGranted(CodeVoter::Show, $code);

        return $this->render('show.html.twig', [
            'code' => $code,
            'network' => $settings->getWifiName(),
            'url' => $settings->getPortalUrl()
        ]);
    }
}
