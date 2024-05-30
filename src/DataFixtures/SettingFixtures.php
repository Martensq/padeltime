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
                ->setMapLink("https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4422.119655573427!2d2.50093447991292!3d48.774099089329695!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e60d632186a0c1%3A0x20594726f144f0e4!2sPadel-Horizon!5e0!3m2!1sfr!2sfr!4v1712687305491!5m2!1sfr!2sfr")
                ->setLinkedinLink("https://www.linkedin.com")
                ->setFacebookLink("https://www.facebook.com")
                ->setInstagramLink("https://www.instagram.com")
                ->setTiktokLink("https://www.tiktok.com")
                ->setCreatedAt(new DateTimeImmutable)
                ->setUpdatedAt(new DateTimeImmutable);


        return $setting;
    }
}
