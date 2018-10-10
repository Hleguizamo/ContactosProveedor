<?php

namespace Cpdg\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CargosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')

            ->add('sinSeguimiento', 'choice', array(
                    'choices'  => array('0' => 'Con Seguimiento Email', '1' => 'Sin Seguimiento Email'),
                    'required' => true,
                    'label' => 'Seguimiento:',
                    'attr' => array(
                              'class' => 'form-large form-control'  
                        )
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cpdg\AdministradorBundle\Entity\Cargos'
        ));
    }
}
