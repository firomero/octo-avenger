<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Planillas\EntidadesBundle\Form\EComponentesSalarialesType;

class ComponenteBonificacionType extends  AbstractType
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
            ))
            ->add('fechaVencimiento','date',array(
                'required' => true,
                'widget'=>'single_text',
                'format' => 'dd/MM/yyyy',
                'label'=>'Vencimiento'
            ))
            ->add('monto','number',array(
                'required' => true,
                'pattern' => "\d+([,.]\d+)?"
            ))
            ->add('permanente','checkbox',array(
                'required' => false
            ))
            ->add('descripcion','textarea',array(
                'required' => false,
                'label' => 'Descripción'
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
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'componente_bonificaciones_type';
    }
} 