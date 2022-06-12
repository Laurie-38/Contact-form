<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
	        ->add('email', RepeatedType::class, [
		        'type' => EmailType::class,
		        'invalid_message' => 'form.registration.error.email.invalid',
		        'required' => true,
		        'first_options'  => ['label' => 'form.registration.placeholder.email'],
		        'second_options' => ['label' => 'form.registration.placeholder.email2'],
		        'constraints' => [
			        new NotBlank([
				        'message' => 'form.registration.error.email.blank',
			        ]),
		        ]
	        ])
	        ->add('password', RepeatedType::class, [
		        'type' => PasswordType::class,
		        'invalid_message' => 'form.registration.error.password.invalid',
		        'options' => ['attr' => ['class' => 'password-field']],
		        'required' => true,
		        'first_options'  => ['label' => 'form.registration.placeholder.password'],
		        'second_options' => ['label' => 'form.registration.placeholder.password2'],
		        'attr' => [
			        'class' => 'col-md-6'
		        ],
		        'constraints' => [
			        new NotBlank([
				        'message' => 'form.registration.error.password.blank',
			        ]),
		        ]
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
