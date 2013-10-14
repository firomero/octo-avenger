<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoType extends AbstractType
{
    public function __construct($bDisplayOptionalfields=false)
	{
	  $this->bDisplayOptionalfields=$bDisplayOptionalfields;
	}
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('segundoApellido')
            ->add('primerApellido')
            ->add('cedula')
            ->add('tallaCalzado')
            ->add('tallaPantalon')
            ->add('tallaCamisa')
            ->add('peso')
            ->add('estatura')
            ->add('estadoCivil')
			->add('salario')
            ->add('email')
            ->add('cantidadDeuda')
            ->add('otroIngreso')
            ->add('tipoPagoCasa')
            ->add('sexo');
			
			if($this->bDisplayOptionalfields){
			
             $builder->remove('empleado');
			 $builder->remove('tallaCalzado');
             $builder->remove('tallaPantalon');
             $builder->remove('tallaCamisa');
             $builder->remove('peso');
             $builder->remove('estatura');
             $builder->remove('estadoCivil');
			 $builder->remove('salario');
             $builder->remove('email');
             $builder->remove('cantidadDeuda');
             $builder->remove('otroIngreso');
             $builder->remove('tipoPagoCasa');
             $builder->remove('sexo');
			}
            /*->add('foto')
            
            ->add('tallaCalzado')
            ->add('tallaPantalon')
            ->add('tallaCamisa')
            ->add('peso')
            ->add('estatura')
            ->add('email')
            ->add('cantidadDeuda')
            ->add('otroIngreso')
            ->add('sexo')
            ->add('supervisor')
            ->add('trabajo')
            ->add('horario')

            ->add('cuentasBancos')

            ->add('gastosPrincipales')*/
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleado'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cempleado';
    }
}
