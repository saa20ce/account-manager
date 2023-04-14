<?php
// src/Form/AccountType.php

namespace App\Form;

use App\DTO\AccountDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('companyName', TextType::class, [
                'required' => false,
            ])
            ->add('position', TextType::class, [
                'required' => false,
            ])
            ->add('phone1', TextType::class, [
                'required' => false,
            ])
            ->add('phone2', TextType::class, [
                'required' => false,
            ])
            ->add('phone3', TextType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccountDTO::class,
        ]);
    }
}
