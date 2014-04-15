<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CIncapacidadesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoIncapacidad', 'choice', array(
                'choices' => array(
                    'incapacidad_ccss' => 'Incapacidad CCSS',
                    'incapacidad_ins' => 'Incapacidades INS'
                ),
            ))
            ->add('motivo', 'textarea', array())
            ->add('fechaInicio', 'date', array(
                'widget' => 'single_text',
                'label'=>'Desde'
            ))
            ->add('fechaFin', 'date', array(
                'required' => false,
                'widget' => 'single_text',
                'label'=>'Hasta'
            ))
            ->add('empleado', 'entity', array(
                'class' => 'PlanillasCoreBundle:CEmpleado',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Form\Models\IncapacidadesModel',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cincapacidades';
    }
}
