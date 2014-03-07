<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CAusenciasType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoAusencia','choice',array('choices'=>array('Ausencia',
                                                                 'Permiso sin goce de salario',
                                                                 'Permiso con goce de salario',
                                                                 'Suspención')))
            ->add('motivo','textarea')
            ->add('fechaInicio','date',array('required'=>true,'attr'=>array('id'=>'datetimepickercomienzo'),'widget'=>'single_text','label'=>'Fecha Inicio'))
            ->add('fechaFin','date',array('required'=>true ,'attr'=>array('id'=>'datetimepickerfin'),'widget'=>'single_text','label'=>'Fecha Fin'))
            ->add('empleado',null,array('required'=>true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CAusencias'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_causencias';
    }
}
