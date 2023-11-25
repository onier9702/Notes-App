<?php

namespace App\Form;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectTagsType extends AbstractType
{

    protected $em;
    public function __construct(ManagerRegistry $doctrine) {
        $this->em = $doctrine->getManager();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        
            $form = $event->getForm();

            $formOptions = [
                'class' => Tags::class,
                'choice_label' => 'name',
                'query_builder' => function (TagsRepository $tagsRepository) {
                    // call a method on your repository that returns the query builder
                    // return $userRepository->createFriendsQueryBuilder($user);
                    // return $tagsRepository->getAllTags();
                    return $tagsRepository->createQueryBuilder('t')
                                    ->select('t.name, t.id')
                                    ->distinct();
                },
            ];
            $tags = $this->em->getRepository(Tags::class)->findAll();
            // $formOptions = [
            //     'class' => Tags::class,
            //     'data' => $tags,
            //     // 'choice_label' => 'name',
            //     'choices' => $tags
            // ];

            // create the field, this is similar the $builder->add()
            // field name, field type, field options
            $form->add('tags', EntityType::class, $formOptions);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tags::class,
        ]);
    }
}
