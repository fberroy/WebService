<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCours')
            ->add('nbHeures')
            ->add('typeCours',ChoiceType::class, [
		'choices'=> [
			'CM'=>'CM',
			'TD'=>'TD',
			'TP'=>'TP',
	],
])
            ->add('NbEnseignants')
            ->add('nbGroupes')
            //->add('Ue')
	   // ->add('nomGroupe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
