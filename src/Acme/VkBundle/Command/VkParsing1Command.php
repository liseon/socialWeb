<?php

namespace Acme\VkBundle\Command;

use Acme\MainBundle\Lib\CollectionManager;
use Acme\VkBundle\Lib\VkPostsSearcher;
use AppBundle\Lib\LockHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use \Doctrine\Common\Persistence\ObjectRepository;

use AppBundle\Lib\ConfigHelper;
use Acme\VkBundle\Lib\VkApi;
use Acme\VkBundle\Lib\VkFriendsCollection;
use Acme\VkBundle\Lib\VkPostsCollection;

use Acme\VkBundle\Entity\VkParsingTasks;
use Acme\VkBundle\Entity\VkUsers;

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

    const CAR_BREND_POINTS = 50000;

    const CAR_MODEL_POINTS = 10000;

    const MIN_LEVEL = 205000;

    const PERIOD = 40;

    /** @var ObjectRepository  */
    private $tasksRep;

    /** @var  ObjectRepository */
    private $vkUserRep;

    /** @var  VkApi */
    private $api;

    /** @var  VkPostsSearcher */
    private $searcher;

    /** @var CollectionManager  */
    private $collectionManager;

    public function __construct(Registry $doctrine, VkApi $vkApi, CollectionManager $collectionManager) {
        $this->tasksRep = $doctrine->getRepository('AcmeVkBundle:VkParsingTasks');
        $this->vkUserRep = $doctrine->getRepository('AcmeVkBundle:VkUsers');

        $this->api = $vkApi;
        $this->collectionManager = $collectionManager;

        parent::__construct();
    }

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
        LockHelper::start($this->getName());

        $output->writeln('Start');

        $this->initSearcher();

        if ($id = (int)$input->getArgument('user_id')) {
            $output->writeln('Vk_id: ' . $id);
            $task = $this->tasksRep->findOneBy([
                'vkUserId' => $id,
                'level' => 0,
            ]);
            if (!($task instanceof VkParsingTasks)) {
                $output->writeln("Task not found");
            } else {
                $this->work($task);
            }
        }

        LockHelper::stop();
    }

    private function work(VkParsingTasks $task) {
        $vkId = $task->getVkUserId();

        /** @var VkUsers $vk */
        $vk = $this->vkUserRep->findOneByVkId($vkId);
        $this->api->setToken($vk->getToken());

        $friends = $this->api->fetchFriends($vkId);
        $result = new VkPostsCollection();

        $postsCount = 0;
        $friendsCount = $friends->count();
        varlog("Founded {$friendsCount} friends!");

        //Первый круг друзей
        $result = new VkPostsCollection();
        $this->fetchPostsForUsers($friends, $result, $postsCount, $vk->getId());

        $this->collectionManager->save($result->prepareEntities());
    }

    private function fetchPostsForUsers(
        VkFriendsCollection $friends,
        VkPostsCollection &$result,
        &$postsCount,
        $userId
    ) {
        $block = [];
        do {
            $block[] = $friends->getId();
            if (count($block) == VkApi::EXECUTE_LIMIT) {
                $posts = $this->api->executeWallGet($block, 30);
                $postsCount += $posts->count();

                $result->joinCollection($this->searcher->find($posts, $userId));
                $block = [];
            }
        } while ($friends->getNext());
    }

    private function initSearcher() {
        $this->searcher = new VkPostsSearcher();
        $this->searcher->setLevel(self::MIN_LEVEL);
        $this->searcher->setDays(self::PERIOD);
        $this->searcher->addKeys(ConfigHelper::get('data/keywords'));
        $this->searcher->addGroup(ConfigHelper::get('data/carBrands'), self::CAR_BREND_POINTS);
        $this->searcher->addGroup(ConfigHelper::get('data/carModels'), self::CAR_MODEL_POINTS);
        $this->searcher->setSites(ConfigHelper::get('data/sites'));
    }
}
