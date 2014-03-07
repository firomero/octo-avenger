<?php

namespace Planillas\CoreBundle\Form\Type;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CVacanteType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*->add('boleta','entity',array(
                    'class' => 'BusBPMCoreBundle:Boleta',
                    'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('b')
                            ->leftJoin('b.casocajero','cc')
                            ->leftJoin('b.casobarras', 'cb')
                            ->where('b.fecha=:fecha')
                            ->andWhere('cc IS NOT NULL AND cb IS NULL')
                            ->setParameter('fecha',date_format(new \DateTime(),'Y-m-d'));
                    },
                ))*/
        $builder
            ->add('nombre')
            ->add('cantidadPlazas')
            ->add('descripcion','textarea',array('label'=>'Descripción'))
            ->add('activo',null,array('label'=>''))
            ->add('trabajo','entity',array(
                'class' => 'PlanillasNomencladorBundle:NTrabajo',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('t')
                        ->where('t.id NOT IN (SELECT v.id from PlanillasCoreBundle:CVacante v)');
                       // ->andWhere('cc IS NOT NULL AND cb IS NULL')
                       // ->setParameter('fecha',date_format(new \DateTime(),'Y-m-d'));
                })
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Planillas\CoreBundle\Entity\CVacante'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planillas_corebundle_cvacante';
    }
}
