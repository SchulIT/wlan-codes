<?php

namespace App\Controller;

use App\Settings\ApplicationSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class SettingsController extends AbstractController {

    #[Route(path: '/admin/settings', name: 'settings')]
    public function index(Request $request, ApplicationSettings $settings) {
        $form = $this->createFormBuilder()
            ->add('wifiName', TextType::class, [
                'label' => 'label.wifi_name',
                'data' => $settings->getWifiName(),
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('portalUrl', TextType::class, [
                'label' => 'label.url',
                'data' => $settings->getPortalUrl(),
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $settings->setWifiName($form->get('wifiName')->getData());
            $settings->setPortalUrl($form->get('portalUrl')->getData());

            $this->addFlash('success', 'settings.success');
            return $this->redirectToRoute('settings');
        }

        return $this->render('admin/settings.html.twig', [
            'form' => $form->createView()
        ]);
    }
}