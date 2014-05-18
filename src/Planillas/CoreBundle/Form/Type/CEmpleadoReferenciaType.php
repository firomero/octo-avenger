<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoReferenciaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clasificacionReferencia')
            ->add('fechaCompletado', 'date', array(
                'widget' => 'single_text',
                'format' => 'd/M/y',
                'attr' => array(
                    'class' => 'datepicker-widget',
                    'placeholder' => 'dd/mm/yyyy'
                ),
            ))
            ->add('compentarios')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'empleado_referencia_type';
    }
}
