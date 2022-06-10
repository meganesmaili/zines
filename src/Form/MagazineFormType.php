<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Magazine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MagazineFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=> "Nom du magazine",
                'required' => false
            ])
            ->add('price', MoneyType::class,[
                'label'=> "Prix du magazine",
                'required'=>false
            ])
            ->add('created_at', ChoiceType::class, [
                'choices' => [
                    'now' => new \DateTimeImmutable('now'),
                    'tomorrow' => new \DateTimeImmutable('+1 day'),
                    '1 week' => new \DateTimeImmutable('+1 week'),
                    '1 month' => new \DateTimeImmutable('+1 month'),
                ],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Magazine::class,
        ]);
    }
}
