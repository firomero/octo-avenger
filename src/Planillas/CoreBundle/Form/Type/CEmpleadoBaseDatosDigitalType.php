<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoBaseDatosDigitalType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, array(
                'required' => false,
            ))
            ->add('descripcion', 'textarea', array(
                'label' => 'Descripción',
                'required' => false,
            ))
            ->add('file', 'file', array(
                'label' => 'Archivo',
                'required' => false,
            ))
            ->add('submit', 'submit', array(
                'label' => 'Enviar',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))
            ->add('cancelar', 'submit', array(
                'label' => 'Cancelar',
                'validation_groups' => false,
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleadoBaseDatosDigital',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cempleadobasedatosdigital';
    }
}
