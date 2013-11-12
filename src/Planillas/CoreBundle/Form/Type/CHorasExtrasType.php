<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CHorasExtrasType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidadHoras')
            ->add('motivo','textarea')
            ->add('fechaHorasExtras')
            ->add('empleado',null,array('required'=>true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CHorasExtras'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_chorasextras';
    }
}
