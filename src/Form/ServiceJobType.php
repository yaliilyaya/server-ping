<?php

namespace App\Form;

use App\Entity\ServiceCommand;
use App\Entity\ServiceConnection;
use App\Entity\ServiceJob;
use App\Form\Type\JsonType;
use App\Repository\ServiceCommandRepository;
use App\Repository\ServiceConnectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceJobType extends AbstractType
{
    /**
     * @var ServiceCommandRepository
     */
    private $serviceCommandRepository;
    /**
     * @var ServiceConnectionRepository
     */
    private $serviceConnectionRepository;

    public function __construct(
        ServiceCommandRepository $serviceCommandRepository,
        ServiceConnectionRepository $serviceConnectionRepository
    ) {
        $this->serviceCommandRepository = $serviceCommandRepository;
        $this->serviceConnectionRepository = $serviceConnectionRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('result')
            ->add('connection', ChoiceType::class, [
                'choices'  => $this->findConnectionList(),
                'required' => false
            ])
            ->add('command', ChoiceType::class, [
                'choices'  => $this->findCommandList(),
                'required' => false
            ])
            ->add('data', JsonType::class)
            ->add('isActive', CheckboxType::class, [
                'required' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceJob::class,
        ]);
    }

    /**
     * @return array
     */
    private function findCommandList(): array
    {
        $commandsData = $this->serviceCommandRepository->findAll();
        $commands = new ArrayCollection($commandsData);
        $commandValues = $commands->map(function (ServiceCommand $command) {
            return [
                $command->getType() => $command->getId()
            ];
        })->toArray();

        dump($commandValues);

        return $commandValues ? array_merge(...$commandValues) : [];
    }

    private function findConnectionList()
    {
        $connectionsData = $this->serviceConnectionRepository->findAll();
        $connections = new ArrayCollection($connectionsData);
        $connectionValues = $connections->map(function (ServiceConnection $connection) {
            return [
                $connection->getName() => $connection->getId()
            ];
        })->toArray();

        dump($connectionValues);

        return $connectionValues ? array_merge(...$connectionValues) : [];

    }
}
