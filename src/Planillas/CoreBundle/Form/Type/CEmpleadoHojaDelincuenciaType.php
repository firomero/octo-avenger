<?php

namespace Planillas\CoreBundle\Form\Type;

use Planillas\CoreBundle\Helper\DocumentModelAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoHojaDelincuenciaType extends DocumentModelAbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('nombre', null, array(
                'required' => false,
            ))
            ->add('descripcion', 'textarea', array(
                'label' => 'Descripción',
                'required' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleadoHojaDelincuencia'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cempleadohojadelincuencia';
    }
}
