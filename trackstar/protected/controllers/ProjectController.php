<?php

class ProjectController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request

            array('COutputCache + view', //cache the entire output from the actionView() method for 2 minutes
                'duration'=>120,
                'varyByParam'=>array('id'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $issueDataProvider = new CActiveDataProvider('Issue',
            array('criteria' => array('condition' => 'project_id=:projectId',
                'params' => array(':projectId' => $this->loadModel($id)->id),
            ),
                'pagination' => array('pageSize'=>1,
                ),
            ));
        $this->render('view',array(
            'model'=>$this->loadModel($id),
            'issueDataProvider' => $issueDataProvider,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Project;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Project']))
        {
            $model->attributes=$_POST['Project'];
            if($model->save())
                /*if () {
                //add association, along with the RBAC biz role, to our RBAC hierarchy
                    $auth = Yii::app()->authManager;
                    $biz_rule = 'return isset($params["project"]) && $params["project"]->allowCurrentUser("owner");';
                    $auth->assign("owner",$model->create_user_id,$biz_rule);
                }*/
                $model->assignUser($model->create_user_id,"owner");

            $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Project']))
        {
            $model->attributes=$_POST['Project'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('Project');


        $sysMessage = SysMessage::getLatest();

        /*        if ($sysMessage !== null) {
                    $sysMessageText = $sysMessage->message;
                }
                else {
                    $sysMessageText = null;
                }
        */
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
            'sysMessage' => $sysMessage
        ));
    }

    /**
     * provide a form so that administrators can associate users to the project
     * @param $id int current project
     * @throws CHttpException 403 access restricted
     */
    public function actionAdduser($id)
    {
        $project = $this->loadModel($id);
        if (!Yii::app()->user->checkAccess('createUser',array('project'=>$project))) {
            throw new CHttpException(403,'access restricted');
        }

        $projectUserForm = new ProjectUserForm();
        if (isset($_POST['ProjectUserForm'])) {
            $projectUserForm->attributes =$_POST['ProjectUserForm'];
            $projectUserForm->project = $project;
            //validate userForm
            if ($projectUserForm->validate()) {
                //assign form
                if ($projectUserForm->assign()) {
                    Yii::app()->user->setFlash('success', $projectUserForm->username . " has been added to the project.");
                }
                $projectUserForm->unsetAttributes();
                $projectUserForm->clearErrors();
            }
        }
        $projectUserForm->project = $project;
        $this->render('adduser',array('model'=>$projectUserForm));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new Project('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Project']))
            $model->attributes=$_GET['Project'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Project the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Project::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Project $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='project-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
