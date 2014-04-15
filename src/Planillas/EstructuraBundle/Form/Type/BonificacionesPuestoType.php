<?php

namespace Planillas\EstructuraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BonificacionesPuestoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bonificacion',null, array(
                'required' => true,
                'horizontal_label_class' => 'col-lg-2',
                'horizontal_input_wrapper_class' => 'col-lg-3',
                'widget_form_group' => false,
            ))
            ->add('monto', null, array(
                'required' => true,
                'horizontal_label_class' => 'col-lg-2',
                'horizontal_input_wrapper_class' => 'col-lg-2',
                'widget_form_group' => false,
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bonificaciones_puesto_type';
    }
} 