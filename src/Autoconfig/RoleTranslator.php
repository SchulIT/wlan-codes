<?php

namespace App\Autoconfig;

use Override;
use SchulIT\CommonBundle\Autoconfig\Roles\RoleTranslatorInterface;
use SchulIT\CommonBundle\Autoconfig\Roles\TranslationFileTranslator;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class RoleTranslator implements RoleTranslatorInterface {

    public function __construct(
        private TranslatorInterface $translator,
        private TranslationFileTranslator $translationFileTranslator,
    ) {

    }

    #[Override]
    public function translate(string $roleName): string|null {
        if(preg_match('/^ROLE_(\d+)$/', $roleName, $matches)) {
            $duration = $matches[1];

            return $this->translator->trans(
                'roles.ROLE_CODE_MINS',
                parameters: [
                    '%minutes%' => $duration,
                ],
                domain: 'autoconfig'
            );
        }

        return $this->translationFileTranslator->translate($roleName);
    }
}