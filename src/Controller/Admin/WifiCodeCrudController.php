<?php

namespace App\Controller\Admin;

use App\Entity\WifiCode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WifiCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WifiCode::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('WLAN Code')
            ->setEntityLabelInPlural('WLAN Codes')
            ->setSearchFields(['code']);
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('duration')
            ->add('requestedAt')
            ->add('requestedBy');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code')->setLabel('Code'),
            IntegerField::new('duration')->setLabel('Dauer (in Minuten)'),
            TextEditorField::new('comment')->setLabel('Kommentar'),
            AssociationField::new('requestedBy')->setLabel('abgerufen von'),
            DateField::new('requestedAt')->setLabel('abgerufen am')
        ];
    }

}
