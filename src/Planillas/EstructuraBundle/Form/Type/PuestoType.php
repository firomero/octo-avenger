<?php

namespace Planillas\EstructuraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PuestoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('salario')
            ->add('bonificaciones', 'collection', array(
                'label'     => 'Bonificaciones',
                'type' => new BonificacionesPuestoType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'widget_add_btn' => array('label' => 'adicionar'),
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => array(
                        'label' => "eliminar",
                        'attr' => array(
                            'class' => 'btn btn-danger'
                        )),
                ),
            ))
            ->add('turno')
            ->add('sucursal')
            ->add('cliente')
            ->add('empresa')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\EstructuraBundle\Entity\Puesto',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_estructurabundle_puesto';
    }
}
