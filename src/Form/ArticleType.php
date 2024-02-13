<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //importer la classe de symfony
        //ce sont les truc créer par symfony 'label', 'label_attr',
            ->add('numArticle', TextType::class,[
                //permet de modifier le texte du label
                'label'=>'CODE:',
                //permet d'appliquer le design sur le label
                'label_attr'=>['class'=>'lab30'],
                //permet d'attribuer le design sur le input
                'attr'=>['class'=>'w50 form-control m-3', 'placeholder'=>"Designation de l'article"],
            ])
            ->add('designation', TextType::class,[
                'label'=>'DESIGNATION:',
                'label_attr'=>['class'=>'lab30'],
                'attr'=>['class'=>'w50 form-control m-3'],
            ])
            ->add('prixUnitaire', TextType::class,[
                'label'=>'PRIX UNITAIRE:',
                'label_attr'=>['class'=>'lab30'],
                'attr'=>['class'=>'w50 form-control m-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
