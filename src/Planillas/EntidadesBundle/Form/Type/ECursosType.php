<?php

namespace Planillas\EntidadesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ECursosType extends AbstractType
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
            ->add('nombre')
            ->add('descripcion','textarea',array('label'=>'Descripción'))
            //->add('vence')
            ->add('vence','date',array('attr'=>array('id'=>'datetimepicker'),'widget'=>'single_text'))
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
            'data_class' => 'Planillas\EntidadesBundle\Entity\ECursos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_entidadesbundle_ecursos';
    }
}
