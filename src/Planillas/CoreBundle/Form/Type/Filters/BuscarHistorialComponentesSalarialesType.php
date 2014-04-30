<?php

namespace Planillas\CoreBundle\Form\Type\Filters;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarHistorialComponentesSalarialesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleado', 'entity', array(
                'class' => 'Planillas\CoreBundle\Entity\CEmpleado',
                'required'=>false
            ))
            ->add('tipoComponente', 'choice', array(
                'required'=>false,
                'choices' => array('Rebajo','Bonificación'),
            ))
            ->add('fechaInicio', 'date', array(
                'widget' => 'single_text',
                'label' => 'Desde',
                'required' => false,
                'format' => 'dd/MM/yyyy',
            ))
            ->add('fechaFin', 'date', array(
                'widget' => 'single_text',
                'label' => 'Hasta',
                'required'=>false,
                'format' => 'dd/MM/yyyy',
            ))

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'method' => 'get',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'buscar_historial_componentes_salariales';
    }
} 