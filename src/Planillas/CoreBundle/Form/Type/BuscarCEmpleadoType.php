<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarCEmpleadoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array(
                'label' => 'Nombre',
                'required' => false,
                ))
            ->add('primerApellido','text',array(
                'label' => 'Primer Apellido',
                'required' => false,
            ))
            ->add('segundoApellido','text',array(
                'label' => 'Segundo Apellido',
                'required' => false,
            ))
            ->add('cedula','text',array(
                'label' => 'Cédula',
                'required' => false,
            ))
            ->add('inactivo','checkbox',array(
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
           // 'data_class' => 'Planillas\CoreBundle\Entity\CEmpleado'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'buscar_empleado';
    }
}
