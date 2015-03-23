<?php

namespace Acme\VkBundle\Lib;

use Acme\MainBundle\Entity\Announces;
use Acme\MainBundle\Lib\Interfaces\PostsCollectionInterface;
use Acme\MainBundle\Lib\Abstracts\CollectionAbstract;
use Acme\VkBundle\Entity\VkAttachments;

/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 27.11.2014
 * Time: 21:02
 */

class VkPostsCollection extends CollectionAbstract implements PostsCollectionInterface
{
    public function getPostTime() {
        return $this->getProperty('created');
    }

    public function getPostText() {
        return $this->getProperty('text') . $this->getProperty('copy_text');
    }

    public function getPostId() {
        return $this->getProperty('id');
    }

    public function getOwnerId() {
        return $this->getProperty('to_id');
    }

    /**
     * @return VkPostsCollection
     */
    public function prepareEntities() {
        $this->entities = [];
        $this->reset();
        do {
            $post = new Announces();
            $post->setForUserId($this->getProperty('for_user_id'));
            $post->setSourceType('vk');
            $post->setOwnerId($this->getProperty('to_id'));
//            $post->setOwnerPhoto($this->getProperty(''));
            $post->setPostId($this->getProperty('id'));
            $post->setCopyOwnerId($this->getProperty('copy_owner_id'));
//            $post->setCopyOwnerPhoto($this->getProperty(''));
            $post->setCopyPostId($this->getProperty('copy_post_id'));
            //$post->setFriendId($this->getProperty(''));
            $post->setText($this->getProperty('text'));
            $post->setPoints($this->getProperty('points'));
            $post->setPostCreatedAt(date_create()->setTimestamp($this->getProperty('date')));
            $post->setCopyPostCreatedAt(date_create()->setTimestamp($this->getProperty('copy_post_date')));

            $this->entities[] = $post;

            $row = $this->getCurrent();

            if (!isset($row['attachments'])) {
                continue;
            }

            foreach ($row['attachments'] as $attach) {
                $type = isset($attach['type']) ? $attach['type'] : false;
                if (!in_array($type, ['link', 'photo']) || !isset($attach[$type])) {
                    continue;
                }
                $attachment = new VkAttachments();
                $attachment->setAnnounce($post);
                $attachment->setType($type);
                switch ($type) {
                    case 'photo':
                        isset($attach[$type]['owner_id']) && $attachment->setVkOwner($attach[$type]['owner_id']);
                        $attachment->setSrc($attach[$type]['src']);
                        $attachment->setSrcBig($attach[$type]['src_big']);
                        break;
                    case 'link':
                        $attachment->setUrl($attach[$type]['url']);
                        $attachment->setSrc($attach[$type]['image_src']);
                        break;
                }

                $this->entities[] = $attachment;
            }
        } while ($this->getNext());

        return $this;
    }
}