<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $setting = $this->createSetting();

        $manager->persist($setting);

        $manager->flush();
    }

    private function createSetting(): Setting
    {
        $setting = new Setting();

        $setting->setClubName("Padel Time")
                ->setPeakHoursPrice("10")
                ->setOffPeakHoursPrice("8")
                ->setRacketRentalPrice("2")
                ->setBallPrice("6")
                ->setWeekOpeningHours("10h - 22h")
                ->setWeekEndOpeningHours("10h - 00h")
                ->setEmail("quentin.martens@orange.fr")
                ->setPhone("06 33 47 49 60")
                ->setAddress("3 bis rue de Paris, 94370 Sucy-en-Brie")
                ->setMapLink("https://urlr.me/T5nLY")
                ->setLinkedinLink("https://www.linkedin.com")
                ->setFacebookLink("https://www.facebook.com")
                ->setInstagramLink("https://www.instagram.com")
                ->setTiktokLink("https://www.tiktok.com")
                ->setCreatedAt(new DateTimeImmutable)
                ->setUpdatedAt(new DateTimeImmutable);


        return $setting;
    }
}
