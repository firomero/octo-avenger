<?php

namespace Planillas\CoreBundle\Helper;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentModelAbstractType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => 'Archivo',
                'required' => false,
            ))
            ->add('submit', 'submit', array(
                'label' => 'Enviar',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))
            ->add('cancelar', 'submit', array(
                'label' => 'Cancelar',
                'validation_groups' => false,
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_documentmodel_abstract';
    }
}
