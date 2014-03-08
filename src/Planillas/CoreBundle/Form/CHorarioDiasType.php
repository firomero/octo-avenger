<?php

namespace Planillas\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CHorarioDiasType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$builder->add('airtime', 'time', array(
                'widget'        => 'single_text',
                'with_seconds'  => false,
                'input'         => 'string',
            ));
         * */
        $builder
            ->add('dia',null,array('label'=>'Día'))
            ->add('activo')
            ->add('horaInicio','time',array('attr'=>array('class'=>'input-small'),
                'input'  => 'datetime',
                'with_seconds'  => false,
                'widget'=>'single_text'))
            ->add('horaFin','time',array('attr'=>array('class'=>'input-small'),
                'input'  => 'datetime',
                'with_seconds'  => false,
                'widget'=>'single_text'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CHorarioDias'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_chorariodias';
    }
}
