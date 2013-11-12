<?php

namespace Planillas\EntidadesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class EHistoriaSaludType extends AbstractType
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
            ->add('ultimaEnfermedad')
            ->add('fechaUltimaEnfermedad')
            ->add('fuma', null)
            ->add('fumaFrecuencia')
            ->add('bebe')
            ->add('bebeFrecuencia')
            ->add('empleado')
            ->add('juegosAzar', 'collection', array('type' => new NJuegoAzarType(),'label' => 'Juegos', 'allow_add'    => true, 'by_reference' => false, 'allow_delete' => true))
            ->add('deportes', 'collection', array('type' => new NDeportesType(),'label' => 'Deportes', 'allow_add'    => true, 'by_reference' => false, 'allow_delete' => true));
        
        if($this->bDestruyeEmpleado)
           $builder->remove('empleado');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\EntidadesBundle\Entity\EHistoriaSalud'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_entidadesbundle_ehistoriasalud';
    }
}
