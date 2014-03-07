<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarHorasExtrasType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleado'/*,'entity',array('class'=>'PlanillasCoreBundle:CEmpleado')*/,null,array('required'=>false))
            ->add('cantidadHoras',null,array('required'=>false,'pattern'=>'[0-9]+'))
            ->add('fechaHorasExtras','date',array('attr'=>array('id'=>'datetimepickercomienzo'),'widget'=>'single_text','label'=>'Fecha','required'=>false))
            //->add('fechaFin','date',array('attr'=>array('id'=>'datetimepickerfin'),'widget'=>'single_text','label'=>'Fecha Fin','required'=>false))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_buscar_horas_extras';
    }
}
