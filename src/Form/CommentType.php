<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', TextType::class, [
                // 表单行配置
                'row_attr' => [
                    'class' => 'form-inline'
                ],
                // 单行 label 配置
                'label_attr' => [
                    'class' => 'form-control-sm w-25 mr-3'
                ],
                // 单行 input 配置
                'attr' => [
                    'class' => 'form-control-sm w-50'
                ],
                'required' => true
            ])
            ->add('email', EmailType::class, [
                // 表单行配置
                'row_attr' => [
                    'class' => 'form-inline'
                ],
                // 单行 label 配置
                'label_attr' => [
                    'class' => 'form-control-sm w-25 mr-3'
                ],
                // 单行 input 配置
                'attr' => [
                    'class' => 'form-control-sm w-50'
                ]
            ])
            ->add('message', TextareaType::class, [
                // 表单行配置
                'row_attr' => [
                    'class' => 'form-inline'
                ],
                // 单行 label 配置
                'label_attr' => [
                    'class' => 'form-control-sm w-25 mr-3'
                ],
                // 单行 input 配置
                'attr' => [
                    'class' => 'form-control-sm w-50'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
