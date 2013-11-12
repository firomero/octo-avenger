<?php

namespace Planillas\EntidadesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NJuegoAzarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\NomencladorBundle\Entity\NJuegoAzar',
        ));
    }

    public function getName()
    {
        return 'planillas_entidadesbundle_njuegoazar';
    }
}
