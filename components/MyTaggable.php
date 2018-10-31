<?php
namespace app\components;

use creocoder\taggable\TaggableBehavior;
use yii\db\Query;

class MyTaggable extends TaggableBehavior
{
    public $parent = '';
    /**
     * @return void
     */
    public function afterSave()
    {
        if ($this->_tagValues === null) {
            return;
        }

        if (!$this->owner->getIsNewRecord()) {
            $this->beforeDelete();
        }

        $tagRelation = $this->owner->getRelation($this->tagRelation);
        $pivot = $tagRelation->via->from[0];
        /* @var ActiveRecord $class */
        $class = $tagRelation->modelClass;
        $rows = [];

        foreach ($this->_tagValues as $value) {
            /* @var ActiveRecord $tag */
            $tag = $class::findOne([$this->tagValueAttribute => $value]);

            if ($tag === null) {
                $tag = new $class();
                $tag->setAttribute($this->tagValueAttribute, $value);
            }

            if ($this->tagFrequencyAttribute !== false) {
                $frequency = $tag->getAttribute($this->tagFrequencyAttribute);
                $tag->setAttribute($this->tagFrequencyAttribute, ++$frequency);
            }

            if ($tag->save()) {
                $rows[] = [$this->owner->getPrimaryKey(), $tag->getPrimaryKey(),$this->parent];
            }
        }

        if (!empty($rows)) {
            $this->owner->getDb()
                ->createCommand()
                ->batchInsert($pivot, [key($tagRelation->via->link), current($tagRelation->link), 'parent'], $rows)
                ->execute();
        }
    }
    /**
     * @return void
     */
    public function beforeDelete()
    {
        $tagRelation = $this->owner->getRelation($this->tagRelation);
        $pivot = $tagRelation->via->from[0];

        if ($this->tagFrequencyAttribute !== false) {
            /* @var ActiveRecord $class */
            $class = $tagRelation->modelClass;

            $pks = (new Query())
                ->select(current($tagRelation->link))
                ->from($pivot)
                ->where([key($tagRelation->via->link) => $this->owner->getPrimaryKey(), 'parent' =>$this->parent])
                ->column($this->owner->getDb());

            if (!empty($pks)) {
                $class::updateAllCounters([$this->tagFrequencyAttribute => -1], ['in', $class::primaryKey(), $pks]);
            }
        }

        $this->owner->getDb()
            ->createCommand()
            ->delete($pivot, [key($tagRelation->via->link) => $this->owner->getPrimaryKey(), 'parent' => $this->parent])
            ->execute();
    }
}
