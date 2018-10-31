<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reserv".
 *
 * @property integer $id
 * @property integer $pos_id
 * @property string $book_dt
 * @property string $content
 * @property integer $mins
 * @property integer $num_peple
 * @property string $status
 * @property string $note
 */
class Reserv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reserv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_id', 'book_dt', 'content', 'mins', 'num_people', 'status', 'note'], 'required'],
            [['pos_id', 'mins', 'num_people'], 'integer'],
            [['book_dt'], 'safe'],
            [['note'], 'string'],
            [['content'], 'string', 'max' => 300],
            [['status'], 'string', 'max' => 50],
            [['pos_id'], 'my_req'],
        ];
    }
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['id' => 'pos_id']);
    }
    public function my_req($attribute_name,$params)
    {
        $query = $this->find();
        if ($this->id != null) {
            $query = $query->where(['<>', 'id', $this->id]);
        }
        $query = $query->andWhere(['pos_id' => $this->pos_id, 'status' => 'confirmed'])->with(['position']);
        $cal = $query->andWhere(['LIKE', 'book_dt', date('Y-m-d', strtotime($this->book_dt))])->all();
        if ($cal == null) {
            $select_pos = Position::find()->where(['id' => $this->pos_id])->one();
            if ($this->num_people > $select_pos->qty) {
                $this->addError($attribute_name, Yii::t('app', 'This position do not enough to store'));
            }
        }
        foreach ($cal as $k => $v) {
            if (
                (strtotime($v->book_dt) >= strtotime($this->book_dt) && strtotime($v->book_dt) <= strtotime($this->book_dt.' + '.$this->mins.' minutes'))
                ||
                (strtotime($v->book_dt.' + '.$v->mins.' minutes') >= strtotime($this->book_dt) && strtotime($v->book_dt.' + '.$v->mins.' minutes') <= strtotime($this->book_dt.' + '.$this->mins.' minutes'))) {
                $this->addError($attribute_name, Yii::t('app', 'This position busy schedule'));
            } else {
                if (strtotime($v->book_dt) <= strtotime($this->book_dt) && strtotime($v->book_dt.' + '.$v->mins.' minutes') >= strtotime($this->book_dt.' + '.$this->mins.' minutes')) {
                    $this->addError($attribute_name, Yii::t('app', 'This position busy schedule'));
                } else {
                    if ($this->num_people > $v->position->qty) {
                        $this->addError($attribute_name, Yii::t('app', 'This position do not enough to store'));
                    }
                }
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'pos_id' => Yii::t('app', 'Positions'),
            'book_dt' => Yii::t('app', 'Book Date time (YYYY-mm-dd HH:mm:ss)'),
            'content' => Yii::t('app', 'Content'),
            'mins' => Yii::t('app', 'Minutes'),
            'num_peple' => Yii::t('app', 'Number of People'),
            'status' => Yii::t('app', 'Status'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
}
