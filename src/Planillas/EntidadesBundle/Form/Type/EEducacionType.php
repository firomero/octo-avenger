<?php

namespace Planillas\EntidadesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EEducacionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleado')
            ->add('educacionIdiomas'/*,array(
                        'class' => 'Planillas\NomencladorBundle\Entity\NIdioma',
                        'property' => 'name',
                        'multiple' => true,
                    'expanded' => false,
                        'required' => true,
                        'label' => 'mail.add.theme'

            )*/)
            ->add('informacionEducacional')
            ->add('cursos')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\EntidadesBundle\Entity\EEducacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_entidadesbundle_eeducacion';
    }
}
