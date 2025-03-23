<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\ParameterTypes\StringType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

#[Settings]
class WifiSettings {
    use SettingsTrait;

    #[SettingsParameter(type: StringType::class, label: 'settings.wifi.name.label', formType: TextType::class, formOptions: [ 'required' => false], nullable: true)]
    #[Assert\NotBlank]
    public ?string $wifiName = null;

    #[SettingsParameter(type: StringType::class, label: 'settings.wifi.portal.label', formType: TextType::class, formOptions: [ 'required' => false], nullable: true)]
    #[Assert\NotBlank]
    public ?string $portalUrl = null;
}