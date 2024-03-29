<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label'=>'USERNAME:',
                'label_attr'=>['class'=>'lab30'],
                'attr'=>['class'=>'form-control w5', 'autocomplete'=>'off']
            ])
            //->add('roles') A travailler spécialement dans UserController
            // ->add('roles')
            ->add('plainPassword', PasswordType::class, [ //input fictif
                'label'=>'PASSWORD',
                'label_attr'=>['class'=>'lab30'],
                'attr'=>['class'=>'form-control w5', 'autocomplete'=>'off', 'placeholder'=>'Ne rien saisir pour garder ancienne valeur'],
                'mapped'=>false, //pour empecher symfony de l'enregistrer avec persit et flush
                'required'=>false, //pour que la saisie ne soit pas obligatoire
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
