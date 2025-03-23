<?php

namespace App\Controller;

use App\Settings\ApplicationSettings;
use App\Settings\AppSettings;
use App\Settings\WifiSettings;
use Jbtronics\SettingsBundle\Form\SettingsFormFactoryInterface;
use Jbtronics\SettingsBundle\Manager\SettingsManagerInterface;
use Jbtronics\SettingsBundle\Settings\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class SettingsController extends AbstractController {

    #[Route(path: '/admin/settings', name: 'settings')]
    public function index(Request $request, SettingsManagerInterface $settingsManager, SettingsFormFactoryInterface $formFactory): Response {
        $settings = [
            $settingsManager->createTemporaryCopy(AppSettings::class),
            $settingsManager->createTemporaryCopy(WifiSettings::class)
        ];

        $form = $formFactory->createMultiSettingsFormBuilder($settings)->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            foreach($settings as $setting) {
                $settingsManager->mergeTemporaryCopy($setting);
            }

            $settingsManager->save();

            $this->addFlash('success', 'settings.success');
            return $this->redirectToRoute('settings');
        }

        return $this->render('admin/settings.html.twig', [
            'form' => $form->createView()
        ]);
    }
}