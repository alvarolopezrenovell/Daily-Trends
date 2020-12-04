<?php


namespace App\Form;


use App\Entity\Feed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('title', TextType::class)
          ->add('body', TextareaType::class)
          ->add('image', TextType::class, ['required' => false])
          ->add('source', TextType::class)
          ->add('publisher', TextType::class)
          ->add('publishedAt', DateTimeType::class)
          ->add('submit', SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     * @return string[]
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        return [
          'data_class' => Feed::class
        ];
    }


}