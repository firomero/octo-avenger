<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoTramitePortacionArmaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            ->add('tramites', 'collection', array(
                'type' => new CTramitePortacionArmaType(),
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
            ->add('submit', 'submit', array(
                'label' => 'Enviar',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))
            ->add('cancelar', 'submit', array(
                'label' => 'Cancelar',
                'validation_groups' => false,
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleadoTramitePortacionArma'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cempleadotramiteportacionarma';
    }
}
