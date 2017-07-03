<?php

namespace AppBundle\Form;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @DI\Service("app.form.search")
 * @DI\Tag("form.type")
 */
class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $buttonName = isset($options['data']) && $options['data']->getId() > 0 ? 'Save' : 'Add';

        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
            ])
            ->add('quantity', TextType::class, [
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => $buttonName,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    public function getName()
    {
        return 'app.order';
    }
}
