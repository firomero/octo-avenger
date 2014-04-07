<?php

namespace Planillas\CoreBundle\Form\Type;

use Planillas\EstructuraBundle\Entity\Empresa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SalarioBasePuestoType extends AbstractType
{
    public $bDestruyeEmpleado;

    public function __construct($bDestruyeEmpleado=false)
    {
        $this->bDestruyeEmpleado = $bDestruyeEmpleado;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salarioBase', 'text', array(
                'widget_addon_prepend' => array(
                    'text' => '₡',
                ),
                'attr' => array(
                    'class' => 'col-lg-1',
                    'placeholder' => 'col-lg-1',
                )
            ))
            ->add('seguro','checkbox',array(
                'label'     => null,
                'required'  => false
            ))
            ->add('empleado','hidden',array(
                'data_class'    =>'Planillas\CoreBundle\Entity\CEmpleado',
                'property_path' =>'id'
            ))
            ->add('empresa', 'entity', array(
                'class'     => 'PlanillasEstructuraBundle:Empresa',
                'required'  => false,
            ))
            ->add('cliente','entity', array(
                'class' => 'PlanillasEstructuraBundle:Cliente',
                'required' => false,
            ))
            ->add('sucursal', 'entity', array(
                'class'     => 'PlanillasEstructuraBundle:Sucursal',
                'required'  => false,
            ))
            ->add('turno', 'entity', array(
                'class'     => 'PlanillasEstructuraBundle:Turno',
                'required'  => false,
            ))
            ->add('puesto', 'entity', array(
                'class'     => 'PlanillasEstructuraBundle:Puesto',
                'required'  => false,
            ))
        ;
        if ($this->bDestruyeEmpleado) {
            $builder->remove('empleado');
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Form\Model\SalarioBasePuesto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_salariobasepuesto';
    }
}
