<?php

namespace Planillas\CoreBundle\Form\Type;

use Planillas\CoreBundle\Helper\DocumentModelAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoRegistroLaboralType extends DocumentModelAbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('descripcion', 'textarea', array(
                'label' => 'Descripción',
                'required' => false,
            ))
            ->add('tipoRegistroLaboral', null, array(
                'required' => true,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleadoRegistroLaboral'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cempleadoregistrolaboral';
    }
}
