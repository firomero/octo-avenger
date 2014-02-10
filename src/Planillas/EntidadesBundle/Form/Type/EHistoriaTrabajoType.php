<?php

namespace Planillas\EntidadesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EHistoriaTrabajoType extends AbstractType
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
	    //$idEmpleado=$options['idEmpleado'];
        $builder
            ->add('momento')
            ->add('empresaPatrono')
            ->add('direccion')
            ->add('telefono')
            ->add('salario')
            ->add('nombreJefe')
            ->add('puesto')
			->add('fechaInicio')
            ->add('fechaFin')
            ->add('tiempo')
            ->add('motivoSalida','textarea')
			 ->add('empleado', 'hidden', array('data_class'=>'Planillas\CoreBundle\Entity\CEmpleado', 'property_path'=>'id'))
			 
			//->add('empleado')
			//->add('empleado','entity',array('data_class'=>'Planillas\CoreBundle\Entity\CEmpleado','property_path'=>'id'))
        ;if($this->bDestruyeEmpleado)
           $builder->remove('empleado');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\EntidadesBundle\Entity\EHistoriaTrabajo'
        ));
		 $resolver->setRequired(array(
            ////'idEmpleado'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_entidadesbundle_ehistoriatrabajo';
    }
}
