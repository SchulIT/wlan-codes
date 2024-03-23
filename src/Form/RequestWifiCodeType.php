<?php

namespace App\Form;

use App\Entity\WifiCode;
use App\Repository\WifiCodeRepositoryInterface;
use App\Security\Voter\CodeVoter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\Translation\TranslatorInterface;

class RequestWifiCodeType extends AbstractType {

    public function __construct(private readonly WifiCodeRepositoryInterface $codeRepository, private readonly TranslatorInterface $translator, private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $choices = [ ];

        foreach($this->codeRepository->getAvailableDurations() as $duration) {
            $fakeCode = (new WifiCode())
                ->setDuration($duration);

            if($this->authorizationChecker->isGranted(CodeVoter::Request, $fakeCode)) {
                $choices[$this->translator->trans('label.minutes', ['%count%' => $duration])] = $duration;
            }
        }

        $builder
            ->add('duration', ChoiceType::class, [
                'label' => 'label.duration',
                'constraints' => [
                    new NotNull()
                ],
                'choices' => $choices,
                'attr' => [
                    'class' => 'custom-select'
                ],
                'placeholder' => 'label.select.duration'
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'label.comment',
                'help' => 'label.comment_help',
                'required' => false
            ]);
    }
}