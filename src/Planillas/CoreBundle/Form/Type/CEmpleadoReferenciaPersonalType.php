<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoReferenciaPersonalType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombrePersona')
            ->add('tiempoConocerlo')
            ->add('poseeHijos')
            ->add('lugarResidencia')
            ->add('conocePQDejoLaborar')
            ->add('estadoCivil');

        $builder->add('_referencia', new CEmpleadoReferenciaType(), array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleadoReferenciaPersonal',
        ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleadoReferenciaPersonal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cempleadoreferenciapersonal';
    }
}
