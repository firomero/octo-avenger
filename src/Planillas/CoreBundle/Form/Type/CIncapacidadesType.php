<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CIncapacidadesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoIncapacidad','choice',array('choices'=>array('1'=>'Incapacidad CCSS','2'=>'Incapacidades INS')))
            ->add('motivo','textarea')
            ->add('fechaInicio','date',array('attr'=>array('id'=>'datetimepickercincapacidadesdesde'),'widget'=>'single_text','label'=>'Desde'))
            ->add('fechaFin','date',array('attr'=>array('id'=>'datetimepickercincapacidadeshasta'),'widget'=>'single_text','label'=>'Hasta'))
            ->add('empleado')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CIncapacidades'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cincapacidades';
    }
}
