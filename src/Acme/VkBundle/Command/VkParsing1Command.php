<?php

namespace Acme\VkBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;

use AppBundle\Lib\ConfigHelper;

use Acme\VkBundle\Entity\VkParsingTasks;

/**
 * Парсит стены 1-ого круга друзей
 *
 * You could also extend from Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
 * to get access to the container via $this->getContainer().
 *
 * @author Igor
 */
class VkParsing1Command extends Command
{

    /** @var \Doctrine\Common\Persistence\ObjectRepository  */
    private $tasksRep;

    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this
            ->setName('vk:parsing:first')
            ->setDescription('Vk parsing 1-st circle')
            ->addArgument('user_id', InputArgument::OPTIONAL, 'Vk_id - для отладки', NULL)
            ->setHelp(<<<EOF
Парсит стены первого круга друзей по всем заявкам из очереди.
Работает в 1 поток (ограничение ВК).
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        error_reporting(E_ALL);
        $output->writeln('Start');

        if ($id = (int)$input->getArgument('user_id')) {
            $output->writeln('Vk_id: ', $id);
            $task = $this->tasksRep->findOneBy([
                'vk_user_id' => $id,
                'level' => 0,
            ]);
            $this->work($task);
            exit(0);
        }

        //$carBrands = ConfigHelper::get('data/carBrands');
        //varlog($carBrands);
        //$br = $this->models();
        /*$carModels = ConfigHelper::get('data/carModels');
        varlog($carModels);*/


    }

    private function work(VkParsingTasks $task) {
        varlog($task->getId());
    }
}
