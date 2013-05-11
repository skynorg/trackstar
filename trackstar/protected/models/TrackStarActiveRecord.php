<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fagor
 * Date: 13.04.13
 * Time: 21:26
 * To change this template use File | Settings | File Templates.
 */

abstract class TrackStarActiveRecord extends CActiveRecord {

    /**
     * Prepares create_user_id and update_user_id attributes before saving
     */
    protected function beforeSave()
    {
        if (Yii::app()->user !== null) {
            $id = Yii::app()->user->id;
        }
        else {
            $id = 0;
        }

        if ($this->isNewRecord) {
            $this->create_user_id = $id;
        }

        $this->update_user_id = $id;

        return parent::beforeSave();
    }

    /**
     * some doc
     * @return array
     */
    public function behaviors()
    {

        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
        );
    }
}