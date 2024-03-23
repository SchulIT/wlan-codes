<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotNull;

class ImportCodesType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('duration', IntegerType::class, [
                'label' => 'label.duration',
                'constraints' => [
                    new GreaterThan(0)
                ]
            ])
            ->add('csv', FileType::class, [
                'label' => 'label.file',
                'constraints' => [
                    new NotNull()
                ]
            ]);
    }
}