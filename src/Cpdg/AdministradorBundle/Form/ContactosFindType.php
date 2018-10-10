<?php
namespace Cpdg\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ContactosFindType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder          
           ->add('idProveedor', 'text', array(
                    'label' => "Empresa / Razón Social: ",
                    'required' => false,
                ))
            ->add('nombreContacto', 'text', array(
                    'required' => false,
                ))
            ->add('idCargo', 'text', array(
                    'label' => "Rol: ",
                    'required' => false,
                ))
            ->add('ciudad', 'text', array(
                    'required' => false,
                ))
            ->add('email','text', array(
                    'required' => false,
                ))
            ->add('telefono', 'text', array(
                    'required' => false,
                ))
            ->add('movil', 'text', array(
                    'required' => false,
                ))
            ->add('idArea', 'text', array(
                    'label' => "Departamento: ",
                    'required' => false,
                ))
            ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cpdg\AdministradorBundle\Entity\Contactos'
        ));
    }
}
