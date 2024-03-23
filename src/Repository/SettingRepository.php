<?php

namespace App\Repository;

use App\Entity\Setting;

class SettingRepository extends AbstractRepository implements SettingRepositoryInterface {

    public function findOneByKey(string $key): ?Setting {
        return $this->em->getRepository(Setting::class)
            ->findOneBy([
                'key' => $key
            ]);
    }


    public function findAll(): array {
        return $this->em->getRepository(Setting::class)
            ->findAll();
    }

    public function persist(Setting $setting): void {
        $this->em->persist($setting);
        $this->em->flush();
    }

}