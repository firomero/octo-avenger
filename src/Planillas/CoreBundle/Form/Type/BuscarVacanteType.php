<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarVacanteType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',null,array('label'=>'Plaza','required'=>false))
           // ->add('cantidadPlazas')
           // ->add('descripcion')
            ->add('activo','checkbox',array('label'=>'','required'=>false))
            ->add('trabajo','entity',array('class'=>'PlanillasNomencladorBundle:NTrabajo','property'=>'nombre'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       /* $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CVacante'
        ));*/
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_buscar_vacante';
    }
}
