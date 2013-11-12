<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarSolicitudEmpleoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',null,array('required'=>false))
            ->add('apellidos', null,array('required'=>false))
            ->add('fecha','date',array('required'=>false,'attr'=>array('id'=>'datetimepicker'),'widget'=>'single_text'))
            //->add('telefono',null,array('required'=>false))

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
        return 'planillas_corebundle_buscar_solicitud';
    }
}
