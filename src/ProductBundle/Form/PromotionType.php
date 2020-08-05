<?php

namespace ProductBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description',TextareaType::class,array('attr' => array('class' => 'form-control')))
            ->add('dateDebut',DateType::class,array('attr' => array('class' => 'form-control'),'widget' => 'single_text'))
            ->add('dateFin',DateType::class,array('attr' => array('class' => 'form-control'),'widget' => 'single_text'))
            ->add('remise',TextType::class,array('attr' => array('class' => 'form-control')))
            ->add('marque',EntityType::class,
                array(
                    'class'=>'ProductBundle\Entity\Marque',
                    'choice_label'=>'titre',
                    'multiple'=>false,
                    'placeholder' => "Choisir une marque"
                ))
            ->add('categorie',EntityType::class,array(
                'class'=>'ProductBundle\Entity\Categorie',
                'choice_label'=>'titre',
                'multiple'=>false,
                'placeholder' => "Choisir une Categorie"
            ))
            ->add('produit',EntityType::class,array(
        'class'=>'ProductBundle\Entity\Produit',
        'choice_label'=>'titre',
        'multiple'=>false,
        'placeholder' => "Choisir un produit"
    ))
            ->add('Enregistrer',SubmitType::class,array('attr' => array('class' => 'form-control')));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProductBundle\Entity\Promotion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'productbundle_promotion';
    }


}
