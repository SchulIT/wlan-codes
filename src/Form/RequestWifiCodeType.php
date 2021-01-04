<?php

namespace App\Form;

use App\Repository\WifiCodeRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\Translation\TranslatorInterface;

class RequestWifiCodeType extends AbstractType {

    private $codeRepository;
    private $translator;

    public function __construct(WifiCodeRepositoryInterface $codeRepository, TranslatorInterface $translator) {
        $this->codeRepository = $codeRepository;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $choices = [ ];

        foreach($this->codeRepository->getAvailableDurations() as $duration) {
            $choices[$this->translator->trans('label.minutes', [ '%count%' => $duration ])] = $duration;
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