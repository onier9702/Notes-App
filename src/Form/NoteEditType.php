<?php

namespace App\Form;

use App\Entity\Notes;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteEditType extends AbstractType
{

    protected $em;

    function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('tag', TagsFieldCustomType::class, [
                'choices' => $options['tag'],
            ])
            ->add('isPublic', CheckboxType::class, [
                'label' => 'Public', 
                'required' => false
            ] )
            ->add('Submit', SubmitType::class)
        ;

        $builder->get('tag')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                // $data = $event->getData();
                // $form->getParent()
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['tag']);
        $resolver->setDefaults([
            'data_class' => Notes::class,
        ]);
    }
}
