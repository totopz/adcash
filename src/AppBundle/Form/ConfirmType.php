<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ConfirmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submitReject', SubmitType::class, [
                'label' => 'Reject',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->add('submitConfirm', SubmitType::class, [
                'label' => 'Confirm',
                'attr' => ['class' => 'btn btn-danger'],
            ]);
    }

    public function getName()
    {
        return 'app.confirm';
    }
}
