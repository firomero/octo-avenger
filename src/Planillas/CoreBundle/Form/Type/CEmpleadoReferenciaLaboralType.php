<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoReferenciaLaboralType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empresa')
            ->add('jefeInmediato')
            ->add('puestoDesempennado')
            ->add('personaReferencia')
            ->add('telefono')
            ->add('fechaInicio', 'date', array(
                'widget' => 'single_text',
                'format' => 'd/M/y',
                'attr' => array(
                    'class' => 'datepicker-widget',
                    'placeholder' => 'dd/mm/yyyy'
                ),
            ))
            ->add('fechaFinal', 'date', array(
                'widget' => 'single_text',
                'format' => 'd/M/y',
                'attr' => array(
                    'class' => 'datepicker-widget',
                    'placeholder' => 'dd/mm/yyyy'
                ),
            ))
            ->add('motivoSalida')
            ->add('recontratable');

        $builder->add('_referencia', new CEmpleadoReferenciaType(), array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleadoReferenciaLaboral',
        ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleadoReferenciaLaboral'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cempleadoreferencialaboral';
    }
}
