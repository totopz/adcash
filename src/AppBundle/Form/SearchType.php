<?php

namespace AppBundle\Form;

use AppBundle\Entity\OrderRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @DI\Service("app.form.search")
 * @DI\Tag("form.type")
 */
class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('period', ChoiceType::class, [
                'choices' => [
                    'All' => '',
                    'Last 7 days' => OrderRepository::PERIOD_LAST_7_DAYS,
                    'Today' => OrderRepository::PERIOD_TODAY,
                ],
                'expanded' => false,
                'required' => false,
            ])
            ->add('term', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'enter search term...',
                ],
            ])
            ->add('searchSubmit', SubmitType::class, [
                'label' => 'search',
            ]);

        $builder->setMethod('GET');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * Remove form name from field names
     *
     * @return null
     */
    public function getBlockPrefix()
    {
        return null;
    }

    public function getName()
    {
        return 'app.search';
    }
}
