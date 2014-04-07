<?php

namespace Planillas\CoreBundle\Form\Type\Filters;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarIncapacidadesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('tipoIncapacidad','choice',array('choices'=>array('0'=>'Incapacidad CCSS','1'=>'Incapacidades INS')))
            ->add('fechaInicio','date',array('required'=>false,'attr'=>array('id'=>'datetimepickerdesde'),'widget'=>'single_text','label'=>'Desde'))
            ->add('fechaFin','date',array('required'=>false,'attr'=>array('id'=>'datetimepickerhasta'),'widget'=>'single_text','label'=>'Hasta'))
            ->add('empleado','text',array('required'=>false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_buscar_incapacidades';
    }
}
