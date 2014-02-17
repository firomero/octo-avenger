<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarAusenciaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleado',null,array('required'=>false)/*,'entity',array('class'=>'PlanillasCoreBundle:CEmpleado')*/)
            ->add('fechaInicio','date',array('attr'=>array('id'=>'datetimepickercomienzo'),'widget'=>'single_text','label'=>'Desde','required'=>false))
            ->add('fechaFin','date',array('attr'=>array('id'=>'datetimepickerfin'),'widget'=>'single_text','label'=>'Hasta','required'=>false))

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
        return 'planillas_corebundle_buscar_ausencia';
    }
}
