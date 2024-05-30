<?php

namespace App\Form;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class SettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clubName', TextType::class)
            ->add('peakHoursPrice', MoneyType::class)
            ->add('offPeakHoursPrice', MoneyType::class)
            ->add('racketRentalPrice', MoneyType::class)
            ->add('ballPrice', MoneyType::class)
            ->add('weekOpeningHours', TextType::class)
            ->add('weekEndOpeningHours', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TelType::class)
            ->add('address', TextType::class)
            ->add('mapLink', TextType::class)
            ->add('linkedinLink', TextType::class)
            ->add('facebookLink', TextType::class)
            ->add('instagramLink', TextType::class)
            ->add('tiktokLink', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
