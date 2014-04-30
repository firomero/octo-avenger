<?php

namespace Planillas\EstructuraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RolesPuestoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empresa')
            ->add('sucursal')
            ->add('cliente')
            ->add('turno')
            ->add('puesto')
            ->add('rol', null, array(
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
            'data_class' => 'Planillas\EstructuraBundle\Entity\RolesPuesto',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_estructurabundle_rolespuesto';
    }
}
