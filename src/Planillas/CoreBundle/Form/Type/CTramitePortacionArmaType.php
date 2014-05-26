<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CTramitePortacionArmaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => 'Archivo',
                'required' => false,
                'horizontal' => true,
                'horizontal_label_class' => 'col-lg-3',
                'horizontal_input_wrapper_class' => 'col-lg-5',
            ))
            ->add('tipoTramite', 'choice', array(
                'choices' => array(
                    'teorico_practico' => 'Teórico práctico',
                    'examen_psicológico' => 'Examen psicológico',
                    'huellas' => 'Huellas',
                    'curso_basico' => 'Curso básico',
                    'carnet_portacion' => 'Carnet portación',
                    'carnet_seguridad_privada' => 'Carnet seguridad privada',
                ),
                'horizontal' => true,
                'horizontal_label_class' => 'col-lg-3',
                'horizontal_input_wrapper_class' => 'col-lg-5',
            ))
        ->add('fecha', 'date', array(
                'horizontal' => true,
                'horizontal_label_class' => 'col-lg-3',
                'horizontal_input_wrapper_class' => 'col-lg-4',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CTramitePortacionArma'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_ctramiteportacionarma';
    }
}
