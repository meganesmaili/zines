<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CategorieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de le categorie",
                'required' => false,
            ])
            ->add('color', ColorType::class, [
                'label'=> "Choix de la couleur",
                'required' =>false,
            ])
            ->add('coverFile', VichImageType::class, [
                'imagine_pattern' => 'thumbnail', //Applique une configuration LiipImagine sur l'image
                'download_label' => false //Enlève le lien de téléchargement
           ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
