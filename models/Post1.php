<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post1".
 *
 * @property string $id
 * @property string $offer_by
 * @property string $department
 * @property string $content
 * @property string $amount
 * @property string $status
 * @property string $payment
 * @property string $accom_doc
 * @property string $offer_by_pay
 * @property string $department_pay
 * @property string $content_pay
 * @property string $accom_doc_pay
 * @property string $payment_pay
 * @property string $note_pay
 * @property string $note
 * @property string $deadline
 * @property string $cost
 * @property string $create_at
 * @property string $create_by
 * @property string $update_at
 * @property string $update_by
 */
class Post1 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post1';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['offer_by', 'department', 'content', 'amount', 'payment', 'deadline'], 'required'],
            [['content', 'content_pay'], 'string'],
            [['deadline', 'create_at', 'create_by', 'update_at', 'update_by'], 'safe'],
            [['offer_by', 'amount', 'offer_by_pay', 'department_pay'], 'string', 'max' => 100],
            [['department', 'accom_doc', 'accom_doc_pay', 'note_pay'], 'string', 'max' => 200],
            [['status', 'payment', 'payment_pay'], 'string', 'max' => 20],
            [['note'], 'string', 'max' => 300],
            [['cost'], 'string', 'max' => 50],
            [['cost'], 'my_required'],
            [['offer_by', 'department', 'content', 'amount', 'payment', 'deadline', 
            'offer_by_pay', 'department_pay', 'payment_pay', 'content_pay', 'cost', 'accom_doc_pay'], 'trim'],
            [['offer_by_pay', 'department_pay', 'payment_pay', 'content_pay', 'cost', 'accom_doc_pay'], 'required', 'on' => 'pay'],

        ];
    }
    public function my_required($attribute_name,$params)
    {
        if ($this->cost != null && (float)$this->cost < 100000) {
            $this->addError($attribute_name, 'Chi phi phai lon hon 100,000');
        }
    }
    // public function scenarios()
    // {
    //     return [
    //         'pay' => ['offer_by_pay', 'department_pay', 'payment_pay', 'content_pay', 'cost', 'accom_doc_pay', 'note_pay']
    //     ];
    // }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'offer_by' => Yii::t('app', 'Offer By'),
            'department' => Yii::t('app', 'Department'),
            'content' => Yii::t('app', 'Content'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
            'payment' => Yii::t('app', 'Payment'),
            'accom_doc' => Yii::t('app', 'Accom Doc'),
            'offer_by_pay' => Yii::t('app', 'Offer By Pay'),
            'department_pay' => Yii::t('app', 'Department Pay'),
            'content_pay' => Yii::t('app', 'Content Pay'),
            'accom_doc_pay' => Yii::t('app', 'Accom Doc Pay'),
            'payment_pay' => Yii::t('app', 'Payment Pay'),
            'note_pay' => Yii::t('app', 'Note Pay'),
            'note' => Yii::t('app', 'Note'),
            'deadline' => Yii::t('app', 'Deadline'),
            'cost' => Yii::t('app', 'Cost'),
            'create_at' => Yii::t('app', 'Create At'),
            'create_by' => Yii::t('app', 'Create By'),
            'update_at' => Yii::t('app', 'Update At'),
            'update_by' => Yii::t('app', 'Update By'),
        ];
    }
}
