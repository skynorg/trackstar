<?php
/**
 * ProjectUserForm uses to form data related adding existing user to a project.
 */
class ProjectUserForm extends CFormModel {

    /**
     * @var string username of the user being added to the project
    */
    public $username;

    /**
     * @var string role to which the user will be associeted within the project
    */
    public $role;

    /**
     * @var Project an instance of the Project AR model class
     */
    public $project;

    private $_user;

    /**
     * Declare rules to validate form (username and password)
    */
    public function rules()
    {
        return array(
            array('username, role','required'),
            //username needs to be checked for existence
            array('username','exist','className'=>'User'),
            array('username','verify')
        );
    }


   /**
    * verify method defined in rules
    *
    */
    public function verify($attributes, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::model()->findByAttributes(array('username'=>$this->username));
            if ($this->project->isUserInProject($user->id)) {
                $this->addError('username','This user has already been added to project');
            }
            else {
                $this->_user = $user;
            }
        }
    }

    /**
     * assign user to the project
     * @return bool
     */
    public function assign()
    {
        if ($this->_user instanceof User) {
            //assign the user, in the specified role to the project
            $this->project->assignUser($this->_user->id, $this->role);

            //add association, along with the RBAC biz role, to our RBAC hierarchy
            $auth = Yii::app()->authManager;
            $biz_rule = 'return isset($params["project"]) && $params["project"]->allowCurrentUser("' . $this->role . '");';
            $auth->assign($this->role,$this->_user->id,$biz_rule);
            return true;
        }
        else {
            $this->addError('username','Error when attempting the assign user to the project');
            return false;
        }
    }

    public function createUsernameList()
    {
        $command = Yii::app()->db->createCommand();
        $command->select('username')->from('tbl_user');
        $rows = $command->queryAll();
        $username_list = array();
        //format it for use with auto complete widget
        foreach ($rows as $row) {
            $username_list[] = $row['username'];
        }
        return $username_list;
    }
}