<?php

namespace Acme\VkBundle\Lib;

use Acme\MainBundle\Lib\Abstracts\CollectionAbstract;
use Acme\MainBundle\Lib\Abstracts\PostsCollection;
use Acme\MainBundle\Lib\Abstracts\SearcherAbstract;


/**
 * Движок для поиска в постах Вк.
 */

class VkPostsSearcher extends SearcherAbstract
{
    /**
     * Вернет коллекцию постов, которые удовлетворяют условиям:
     * 1) Были опубликованы не позже, чем $period дней назад.
     * 2) Создержат в посте вхождение всех требуемых подстрок, если параметр $isHard включен!
     * Или хотя бы одной строки, если $isHard == false
     * Перед поиском текст и ключи нормализуется!
     *
     * @param VkPostsCollection $collection
     * @param int $userId
     * @return VkPostsCollection $result
     *
     */
    public function find(VkPostsCollection $collection, $userId = 0) {
        $result = new VkPostsCollection();
        $collection->reset();
        $extremeTime = time() - $this->getPeriod();

        do {
            if ($extremeTime < $collection->getPostTime()) {
                //Цикл не прерываем, т.к. мы точно не можем быть уверены в верной сортировке по дате.
                continue;
            }
            $points = $this->analizeText($collection->getPostText());
            $attachPoints = $this->calcPointsForAttachments($collection->getProperty('attachments'));
            $total = $points + $attachPoints;
            if ($total >= $this->level) {
               // varlog("attach: {$attachPoints}   Total: {$total}");
              //  varlog("*************************************************");
                $collection->setProperty('for_user_id', (int)$userId);
                $collection->setProperty('points', $total);
                $result->add($collection->getCurrent());
            }
        } while ($collection->getNext());

        return $result;
    }


    private function calcPointsForAttachments($attachments) {
        if (!is_array($attachments)) {

            return 0;
        }
        $result = 0;
        $hasPhoto = false;
        foreach ($attachments as $attach) {
            if (!$hasPhoto && $attach['type'] == 'photo') {
                $hasPhoto = true;
                $result += $this->pointsForPhoto;
            }
            if ($attach['type'] == 'link' && isset($attach['link']['url'])) {
                foreach ($this->sites as $site) {
                    if (strpos($attach['link']['url'], $site['site']) !== false) {
                        $result += $site['weight'];
                    }
                }
            }
        }

        return $result;
    }

}