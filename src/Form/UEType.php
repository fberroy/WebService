<?php

namespace App\Form;

use App\Entity\UE;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Intitule')
            ->add('formation',  ChoiceType::class, ['choices' => [ 'Master Informatique' => 'Master Informatique', 'Master Miage' => 'Master Miage', 'Licence' => 'Licence' ] ])
            ->add('semestre')
            ->add('statut',  ChoiceType::class, ['choices' => [ 'Facultatif' => 'Facultatif', 'Obligatoire' => 'Obligatoire'] ])
	    ->add('effectif')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UE::class,
        ]);
    }
}
