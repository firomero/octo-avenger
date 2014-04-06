<?php

namespace Planillas\EstructuraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PuestoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('salario')
            ->add('rol')
            ->add('bonificacion')
            ->add('turno')
            ->add('sucursal')
            ->add('cliente')
            ->add('empresa')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\EstructuraBundle\Entity\Puesto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_estructurabundle_puesto';
    }
}
