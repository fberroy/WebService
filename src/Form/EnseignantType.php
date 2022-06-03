<?php

namespace App\Form;

use App\Entity\Enseignant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            //->add('prenom')
            ->add('identifiant')
            //->add('motDePasse', PasswordType::class, ['required'=>false])
            ->add('mail', EmailType::class, ['required'=>false])
            ->add('nbUC')
            ->add('nomDepartement')
            ->add('statutEnseignant')
	    ->add('archive')
            ->add('AccesAdmin')
	    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enseignant::class,
        ]);
    }
}
