<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FileUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'upload_file',
                FileType::class,
                [
                    'label'       => 'Select the file containing the poker hands:',
                    'mapped'      => false, // Tell that there is no Entity to link
                    'required'    => true,
                    'constraints' => [
                        new File(
                            [
                                'mimeTypes'        => [ // We want to let upload only txt files
                                    'text/plain',
                                ],
                                'mimeTypesMessage' => "This document isn't valid.",
                            ]
                        ),
                    ],
                ]
            )
            ->add('send', SubmitType::class);
    }
}
