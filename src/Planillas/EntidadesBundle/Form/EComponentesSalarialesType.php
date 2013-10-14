<?php

namespace Planillas\EntidadesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EComponentesSalarialesType extends AbstractType
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
            ->add('componente','choice',array('choices'=>self::componentesChoices()))
            ->add('cantidad')
            ->add('moneda','choice',array('choices'=>self::monedaChoices()))
            ->add('descripcion','textarea')
            ->add('fechaVencimiento')
            ->add('empleado', 'hidden', array('data_class'=>'Planillas\CoreBundle\Entity\CEmpleado', 'property_path'=>'id'))
        ;
	    if($this->bDestruyeEmpleado)
           $builder->remove('empleado');
    }
    public static function monedaChoices()
    {
        return array('Colon costarricence','Dolar americano');
    }
	public static function componentesChoices()
    {
        return array('Rebajo','Bonificacion');
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\EntidadesBundle\Entity\EComponentesSalariales'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_entidadesbundle_ecomponentessalariales';
    }
}
