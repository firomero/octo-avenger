<?php

namespace Planillas\CoreBundle\Form\Type;

use Planillas\CoreBundle\Form\CHorarioDiasType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CHorarioType extends AbstractType
{
    public $bDestruyeEmpleado;

    public function __construct($bDestruyeEmpleado = false)
    {
        $this->bDestruyeEmpleado = $bDestruyeEmpleado;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('horarioDias', 'collection', array(
                    'type' => new CHorarioDiasType(),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => true))

                ->add('titulo',null,array('label'=>'Título'));
                //->add('empleado', 'hidden', array('data_class' => 'Planillas\CoreBundle\Entity\CEmpleado', 'property_path' => 'id'));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CHorario'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_chorario';
    }

}
