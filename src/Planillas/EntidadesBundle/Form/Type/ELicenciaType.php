<?php

namespace Planillas\EntidadesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ELicenciaType extends AbstractType
{
    public $bDestruyeEmpleado;

    public function __construct($bDestruyeEmpleado=false)
    {
       $this->bDestruyeEmpleado = $bDestruyeEmpleado;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoLicencia')
            #->add('vence')
            ->add('vence','date',array('required'=>true,'attr'=>array('id'=>'datetimepickervence'),'widget'=>'single_text','label'=>'Vence'))
            ->add('empleado', 'hidden', array('data_class'=>'Planillas\CoreBundle\Entity\CEmpleado', 'property_path'=>'id'))
        ;

        if($this->bDestruyeEmpleado)
           $builder->remove('empleado');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\EntidadesBundle\Entity\ELicencia'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_entidadesbundle_elicencia';
    }
}
