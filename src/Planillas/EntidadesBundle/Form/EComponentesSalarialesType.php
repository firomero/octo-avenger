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
            ->add('moneda','choice',array('choices'=>self::monedaChoices()))
            ->add('tipoDeuda','choice',array('choices'=>array('Uniformes','Sanciones','Préstamos')))
            ->add('cantidad',null,array('required'=>true,'pattern'=>"\d+([,.]\d+)?"))
            ->add('montoTotal',null,array('required'=>true,'pattern'=>"\d+([,.]\d+)?"))
            
            ->add('numeroCuotas',null,array('label'=>'Número de cuotas','required'=>true,'pattern'=>"\d+"))
            ->add('permanente','checkbox',array('required'=>false))
            #->add('pagado','choice',array('choices'=>array('No pagado','Pagado')))
            ->add('fechaInicio','date',array('required'=>true,'attr'=>array('id'=>'datetimepickeriniciodeudas'),'widget'=>'single_text','label'=>'Inicio de pago'))
            ->add('descripcion','textarea',array('required'=>false,'label'=>'Descripción'))
            ->add('fechaVencimiento','date',array('required'=>true,'attr'=>array('id'=>'datetimepickerfechavencimiento'),'widget'=>'single_text','label'=>'Vencimiento'))
            ->add('empleado', 'hidden', array('data_class'=>'Planillas\CoreBundle\Entity\CEmpleado', 'property_path'=>'id'))
        ;
	    if($this->bDestruyeEmpleado)
           $builder->remove('empleado');
    }
    public static function monedaChoices()
    {
        return array('Colón costarricence','Dolar americano');
    }
	public static function componentesChoices()
    {
        return array('Rebajo','Bonificación');
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
