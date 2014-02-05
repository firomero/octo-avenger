<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CSalarioBaseType extends AbstractType
{
    var $bDestruyeEmpleado;
    
    public function __construct($bDestruyeEmpleado=false){
       $this->bDestruyeEmpleado = $bDestruyeEmpleado;    
    }
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salarioBase')
            ->add('seguro','checkbox',array('label'=>null,'required'=>false))
            ->add('periodoPago','choice',array('choices'=>array('Semanal','Quincenal','Mensual')))
            ->add('empleado','hidden',array('data_class'=>'Planillas\CoreBundle\Entity\CEmpleado','property_path'=>'id'))
        ;
		if($this->bDestruyeEmpleado)
		{
		  $builder->remove('empleado');
		}
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CSalarioBase'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_csalariobase';
    }
}
