<?php

namespace Planillas\CoreBundle\Form\Type;

use Planillas\CoreBundle\Helper\DocumentModelAbstractType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CEmpleadoDatosAdjuntosRRHHType extends DocumentModelAbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('fecha', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => true,
             ))
            ->add('observaciones', 'textarea', array(
                'required' => false,
            ))
            ->add('tipoDatoAdjunto', null, array(
                'required' => true,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CEmpleadoDatosAdjuntosRRHH'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cempleadodatosadjuntosrrhh';
    }
}
