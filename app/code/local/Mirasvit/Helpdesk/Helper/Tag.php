<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Help Desk MX
 * @version   1.0.7
 * @build     907
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Helpdesk_Helper_Tag
{
    public function addTags($ticket, $tags)
    {
        if (is_string($tags)) {
            $tags = explode(',', $tags);
        }
        $ticket->loadTagIds();
        $tagIds = $ticket->getTagIds();
        foreach ($tags as $tagName) {
            if (!$tag = $this->getTag($tagName)) {
                continue;
            }
            array_push($tagIds, $tag->getId());
        }
        $ticket->setTagIds(array_unique($tagIds));
    }

    public function removeTags($ticket, $tags)
    {
        if (is_string($tags)) {
            $tags = explode(',', $tags);
        }
        $tagIds = $ticket->getTagIds();
        foreach ($tags as $tagName) {
            if (!$tag = $this->getTag($tagName)) {
                continue;
            }
            if (($key = array_search($tag->getId(), $tagIds)) !== false) {
                unset($tagIds[$key]);
            }
        }
        $ticket->setTagIds($tagIds);
    }

    public function setTags($ticket, $tags)
    {
        if (is_string($tags)) {
            $tags = explode(',', $tags);
        }
        $tagIds = array();
        foreach ($tags as $tagName) {
            $tag = $this->getTag($tagName);
            if (!$tag) {
                continue;
            }
            $tagIds[] = $tag->getId();
        }
        $ticket->setTagIds($tagIds);
    }

    public function getTag($tagName)
    {
        $tagName = trim($tagName);
        if (!$tagName) {
            return false;
        }
        $collection = Mage::getModel('helpdesk/tag')->getCollection()
            ->addFieldToFilter('name', $tagName);
        if ($collection->count()) {
            $tag = $collection->getFirstItem();
        } else {
            $tag = Mage::getModel('helpdesk/tag')->setName($tagName)->save();
        }

        return $tag;
    }

    public function getTagsAsString($ticket)
    {
        $ticket->loadTagIds();
        if (count($ticket->getTagIds()) == 0) {
            return '';
        }

        $collection = Mage::getModel('helpdesk/tag')->getCollection()
                        ->addFieldToFilter('tag_id', $ticket->getTagIds());
        $arr = array();
        foreach ($collection as $tag) {
            $arr[] = $tag->getName();
        }

        return implode(', ', $arr);
    }
}