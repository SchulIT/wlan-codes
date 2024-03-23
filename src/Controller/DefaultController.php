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
use App\WifiCodes\NotGrantedException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {

    private const ItemsPerPage = 25;

    #[Route(path: '/', name: 'index')]
    public function index(): Response {
        return $this->redirectToRoute('dashboard');
    }

    #[Route(path: '/code', name: 'dashboard')]
    public function dashboard(WifiCodeRepositoryInterface $repository, CodeManager $codeManager, Request $request): Response {
        /** @var User $user */
        $user = $this->getUser();

        $page = $request->query->getInt('page', 1);
        $pages = ceil((float)$repository->countAllByUser($user) / self::ItemsPerPage);

        if($page <= 0 || $page > $pages) {
            $page = 1;
        }

        $codes = $repository->findAllByUser($user, ($page - 1) * self::ItemsPerPage, self::ItemsPerPage);

        $form = $this->createForm(RequestWifiCodeType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $code = $codeManager->requestCode($form->get('duration')->getData(), $form->get('comment')->getData());

                $this->addFlash('success', 'request.success');

                return $this->redirectToRoute('show_code', [
                    'uuid' => $code->getUuid()
                ]);
            } catch (NoCodeAvailableException) {
                $this->addFlash('error', 'request.error.no_codes');
            } catch(NotGrantedException) {
                $this->addFlash('error', 'request.error.not_granted');
            } catch (Exception) {
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

    #[Route(path: '/code/{uuid}', name: 'show_code')]
    public function show(WifiCode $code, ApplicationSettings $settings): Response {
        $this->denyAccessUnlessGranted(CodeVoter::Show, $code);

        return $this->render('show.html.twig', [
            'code' => $code,
            'network' => $settings->getWifiName(),
            'url' => $settings->getPortalUrl()
        ]);
    }
}
