<?php

namespace Cpdg\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuario', 'text', array('disabled'=>true))
            ->add('nombre')
            ->add('email', 'email')
            ->add('contrasena', 'repeated', 
        array('type' => 'password', 'invalid_message' => 'La contraseña no coincide.',
        'first_options'  => array('label' => 'Contraseña'),
        'second_options' => array('label' => 'Confirme la contraseña')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cpdg\UsuarioBundle\Entity\Usuario'
        ));
    }
}
