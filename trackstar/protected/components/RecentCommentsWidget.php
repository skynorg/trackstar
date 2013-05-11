<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naa
 * Date: 24.04.13
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
  */

class RecentCommentsWidget extends CWidget {

    /**
     * @var Comment
     */
    private $_comments;

    public $display_comment = 5;
    public $project_id = null;

    /**
     * initialize widget
     */
    public function init()
    {
        if (null !== $this->project_id) {
            $this->_comments = Comment::model()->with(array('issue'=>array('condition'=>'project_id=' . $this->project_id)))->recent($this->display_comment)->findAll();
        }
        else {
            $this->_comments = Comment::model()->recent($this->display_comment)->findAll();
        }
    }

    /**
     * @return Comment
     */
    public function getData()
    {
        return $this->_comments;
    }

    public function run()
    {
        //this method is called by CController::endWidget()
        $this->render('recentCommentsWidget');
    }
}