<?php

namespace Acme\MainBundle\Lib\Abstracts;

use AppBundle\Lib\StringHelper;
use AppBundle\Lib\Constants;

/**
 *  Базовый класс для поиска
 */

abstract class SearcherAbstract
{

    /**
     * @var array ['ключ' => Баллы за вхождение]
     * За каждое вхождение ключа из этого массива будут начислены балы, но не более 1 раза за 1 слово
     */
    private $keys =[];

    /**
     * @var array [['keys' => ['key1', 'key2', ...], 'points' => Баллы за вхождение], ]
     * Можно загрузить неограниченое кол-во групп слов.
     * Баллы будут начислены за вхождение хотя бы одного слова из группы.
     * Баллы будут начислены только 1 раз.
     */
    private $groups = [];

    /**
     * @var array [['site' => 'mysite.ru', 'weight' => 50]]
     */
    protected $sites = [];

    /** @var int За сколько дней искать */
    protected $days = 30;

    /** @var int Минимальный уровень баллов, чтобы считать сущность опознанной  */
    protected $level = 100;

    /** @var int Кол-во баллов, если все вхождения были обнаружены */
    private $maxPoints = 0;

    /** @var int Баллы за фотку */
    protected $pointsForPhoto = 20;

    /**
     * @param array $sites
     */
    public function setSites(array $sites)
    {
        $this->sites = $sites;
    }

    /**
     * @param int $pointsForPhoto
     */
    public function setPointsForPhoto($pointsForPhoto)
    {
        $this->pointsForPhoto = (int)$pointsForPhoto;
    }



    /**
     * @param array $keys
     */
    public function setKeys($keys)
    {
        $this->keys = $keys;
    }

    /**
     * @param int $days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }

    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }






    public function addKeys(array $keys) {
        foreach ($keys as $key => $point) {
            $this->addKey($key, $point);
        }
    }

    public function addKey($key, $points) {
        $points = (int)$points;
        if (!$points > 0) {
            return false;
        }
        $key = StringHelper::normalize($key);
        $this->keys[$key] = $points;

        return true;
    }

    public function addGroup($group, $points) {
        $points = (int)$points;
        if (!$points > 0) {
            return false;
        }
        $groupNorm = [];
        foreach ($group as $val) {
            $groupNorm[] = StringHelper::normalize($val);
        }
        $this->groups[] = [
            'keys' => $groupNorm,
            'points' => $points,
        ];

        return true;
    }

    /**
     * Производит анализ текста
     * 1) Приводит текст к нормальному виду
     * 2) За каждое вхождение ключа начисляет соответствующее количество баллов.
     *    При этом количество вхождений одинакового ключа значения не имеет!
     * 3) Из всех групп ключей пытается найти хотя бы 1 вхождение для каждой группы и начислить за это баллы
     * 4) Сравнивает результат с минимальным уровнем.
     *
     * @param $text
     * @return bool
     */
    public function analizeText($text) {
        $utext = $text;
        $text = StringHelper::normalize($text);
        $result = 0;
        $match = [];
        foreach ($this->keys as $key => $point) {
            if (!mb_strpos($text, $key) === false) {
                $result += $point;
                $match[] = $key;
            }
        }
        foreach ($this->groups as $group) {
            $keys = $group['keys'];
            $points = $group['points'];
            foreach ($keys as $key) {
                if (!mb_strpos($text, $key) === false) {
                    $result += $points;
                    $match[] = $key;
                    break;
                }
            }
        }

        if ($result >= $this->level) {
            varlog($utext);
            varlog("KEYS:");
            foreach ($match as $m) {
                varlog(" == {$m}");
            }

            varlog("  ----------- {$result}  ------------");
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getPeriod() {
        return (int)$this->days * Constants::DAY;
    }

    /**
     * @return int
     */
    private function getMaxPoints() {
        if (!$this->maxPoints) {
            foreach ($this->keys as $point) {
                $this->maxPoints += $point;
            }
        }

        return $this->maxPoints;
    }






}