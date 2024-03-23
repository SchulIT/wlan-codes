<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminMenuBuilder extends AbstractMenuBuilder {

    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, TranslatorInterface $translator) {
        parent::__construct($factory, $tokenStorage, $authorizationChecker, $translator);
    }

    public function adminMenu(array $options): ItemInterface {
        $root = $this->factory->createItem('root')
            ->setChildrenAttributes([
                'class' => 'navbar-nav float-lg-right'
            ]);

        if($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu = $root->addChild('admin', [
                'label' => ''
            ])
                ->setExtra('icon', 'fa fa-cogs')
                ->setAttribute('title', $this->translator->trans('administration.label'))
                ->setExtra('menu', 'admin')
                ->setExtra('menu-container', '#submenu')
                ->setExtra('pull-right', true);

            $menu->addChild('settings.label', [
                'route' => 'settings'
            ])
                ->setExtra('icon', 'fa fa-wrench');

            $menu->addChild('codes.label', [
                'route' => 'codes'
            ])
                ->setExtra('icon', 'fas fa-list');

            $menu->addChild('logs.label', [
                'route' => 'admin_logs'
            ])
                ->setExtra('icon', 'fas fa-clipboard-list');

            $menu->addChild('administration.ea', [
                'uri' => '/admin/ea'
            ])
                ->setLinkAttribute('target', '_blank')
                ->setExtra('icon', 'fas fa-tools');
        }

        return $root;
    }
}