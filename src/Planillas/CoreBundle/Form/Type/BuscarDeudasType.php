<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarDeudasType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montoTotal','money',array('required'=>false))
            ->add('tipoDeuda','choice',array('choices'=>array('Uniformes','Sanciones','Préstamos')))
            ->add('pagado','choice',array('choices'=>array('No pagado','Pagado')))
            ->add('fechaInicio','date',array('required'=>false,'attr'=>array('id'=>'datetimepickeriniciodeudas'),'widget'=>'single_text','label'=>'Inicio de pago'))
            ->add('empleado',null,array('required'=>false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
           // 'data_class' => 'Planillas\CoreBundle\Entity\CDeudas'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_buscar_deudas';
    }
}
