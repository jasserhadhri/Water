<?php

namespace ProductBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre',TextType::class,array('attr' => array('class' => 'form-control')))
                ->add('description',TextType::class,array('attr' => array('class' => 'form-control')))
                ->add('stock',TextType::class,array('attr' => array('class' => 'form-control')))
                ->add('prix',TextType::class,array('attr' => array('class' => 'form-control')))
                ->add('type',TextType::class,array('attr' => array('class' => 'form-control')))
                ->add('image',FileType::class,array('attr' => array('required'=>false,'class' => 'form-control file'),'data_class' => null,'label'=>'Choisir une image'))
                ->add('dateFabrication',DateTimeType::class,array('widget' => "single_text",'attr' => array('class' => 'form-control')))
                ->add('dateExp',DateTimeType::class,array('widget' => "single_text",'attr' => array('class' => 'form-control')))

              ->add('marque',EntityType::class,
                  array(
                       'class'=>'ProductBundle\Entity\Marque',
                       'choice_label'=>'titre',
                       'multiple'=>false,
                       'placeholder' => "Choisir la marque" ,
                       'label' => 'Marque'
                  ))
                ->add('categorie',EntityType::class,array(
                    'class'=>'ProductBundle\Entity\Categorie',
                    'choice_label'=>'titre',
                    'multiple'=>false,
                    'placeholder' => "Choisir la Categorie" ,
                    'label' => 'Categorie'
                ))
            ->add('Enregistrer', SubmitType::class, array('attr' => array('class' => 'form-control btn btn-primary pull-right')));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProductBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'productbundle_produit';
    }


}
