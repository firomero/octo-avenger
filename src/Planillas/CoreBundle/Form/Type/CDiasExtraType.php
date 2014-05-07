<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CDiasExtraType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha','date',array('required'=>true,'attr'=>array('id'=>'datetimepickerdia'),'widget'=>'single_text','label'=>'Día'))
            ->add('descripcion', 'textarea')
            ->add('motivo')
            ->add('empleado','entity', array(
                'required' => true,
                'multiple' => true,
                'class' => 'Planillas\CoreBundle\Entity\CEmpleado',
                'attr' => array(
                    'class' => 'chosen-select',
                ),
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'Planillas\CoreBundle\Entity\CDiasExtra'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cdiasextra';
    }
}
