<?php

namespace Planillas\PaymentsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PeriodoPlanillaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio', 'date',array(
                'widget'=>'single_text',
                'label'=>'Fecha Inicio',
                'required'=>true,
            ))
            ->add('fechaFin', 'date', array(
                'widget'=>'single_text',
                'label'=>'Fecha Fin',
                'required'=>true
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\PaymentsBundle\Form\Models\PeriodoPlanillaModel',
            'csrf_protection' => false,
            'method' => 'GET',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payments_periodo_planilla';
    }
} 