<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder,array $options)
    {
        $builder
        ->add('comment')
        ->add('author')
        ->add('save',SubmitType::class)
        ;
    }

}