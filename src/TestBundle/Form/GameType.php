<?php

namespace TestBundle\Form;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DateTimeParamConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', ['required' => true])
            ->add('nbPlayer', 'integer', ['required' => true])
            ->add('platform', 'text', ['required' => true])
            ->add('launchDate', 'datetime', [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd'
                ]
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => 'TestBundle\Entity\Game'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'testbundle_game';
    }
}
