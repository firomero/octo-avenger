<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoType extends AbstractType
{
    public function __construct($bDisplayOptionalfields = false, $eEmpleado = 0)
    {
        $this->bDisplayOptionalfields = $bDisplayOptionalfields;
        $this->eEmpleado = $eEmpleado;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nombre')
        ->add('segundoApellido')
        ->add('primerApellido')
        ->add('cedula','text',array('label'=>'Cédula','required'=>true,'attr'=>
                              array('pattern'=>"[a-zA-Z0-9]{1,15}",
                                    /*'oninvalid'=>'setCustomValidity("La cédula no es correcta")'*/)))
        ->add('tallaCalzado')
        ->add('tallaPantalon',null,array('label'=>'Talla de Pantalón'))
        ->add('tallaCamisa')
        ->add('peso')
        ->add('estatura')
        ->add('estadoCivil')
        ->add('salario')
        ->add('email')
        ->add('cantidadDeuda')
        ->add('otroIngreso')
        ->add('tipoPagoCasa')
        ->add('sexo')
        ;

        if ($this->bDisplayOptionalfields) {

            $builder->remove('empleado');
            //$builder->remove('supervisor');
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
        /* ->add('foto')

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

          ->add('gastosPrincipales') */
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
