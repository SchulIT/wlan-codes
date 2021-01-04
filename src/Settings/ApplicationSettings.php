<?php

namespace App\Settings;

class ApplicationSettings extends AbstractSettings {
    public function getWifiName(): ?string {
        return $this->getValue('wifi.name');
    }

    public function setWifiName(?string $name): void {
        $this->setValue('wifi.name', $name);
    }

    public function getPortalUrl(): ?string {
        return $this->getValue('wifi.portal.url');
    }

    public function setPortalUrl(?string $url): void {
        $this->setValue('wifi.portal.url', $url);
    }
}