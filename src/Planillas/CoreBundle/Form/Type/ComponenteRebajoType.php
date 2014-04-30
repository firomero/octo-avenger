<?php

namespace Planillas\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Planillas\EntidadesBundle\Form\EComponentesSalarialesType;

class ComponenteRebajoType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleado', 'entity', array(
                'class' => 'Planillas\CoreBundle\Entity\CEmpleado',
            ))
            ->add('tipoDeuda', 'choice', array(
                'choices' => EComponentesSalarialesType::tipoDeudas(),
            ))
            ->add('fechaInicio','date',array(
                'required' => true,
                'format' => 'dd/MM/yyyy',
                'widget'=>'single_text',
                'label' => 'Inicio de pago'
            ))
            ->add('permanente','checkbox',array(
                'required' => false
            ))
            ->add('montoTotal','number',array(
                'required' => true,
            ))
            ->add('numeroCuotas','number',array(
                'required' => false,
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'method' => 'POST',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'componente_rebajos_type';
    }
} 