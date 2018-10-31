<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use app\models\TaskUser;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
        'delete' => ['POST'],
        ],
        ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Task::find(),
            ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Displays a single Task model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                ]);
        }
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                ]);
        }
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('delete_success', 'delete success');
        }else
        {
            Yii::$app->session->setFlash('delete_unsuccess', 'delete unsuccess');
        }

        return $this->redirect(Yii::$app->request->getReferrer());
    }
    /**
    *delete ajax
    *
    *
    **/
    public function actionRemove($id)
    {
        if (Yii::$app->request->isAjax) {
            if ($this->findModel($id) != null) {
                TaskUser::deleteAll(['task_id' => $id]);
                if ($this->findModel($id)->delete()) {
                    echo json_encode($this->renderData());
                }
            }else
            {
                Yii::$app->session->setFlash('delete_unsuccess', 'delete unsuccess');
            }
        }
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
    * this function*
    *
    **/
    public function actionList()
    {
        if (Yii::$app->request->isAjax) {
            $fromTimeZone="Asia/Ho_Chi_Minh";
            $toTimeZone = "UTC";
            if (isset($_POST['action'])) {
                if ($_POST['action'] == 'create') {
                    if (isset($_POST['description']) && (count($_POST['description']) > 0)) {
                        // $date = date('Y-m-d');
                        $task = new Task();
                        $task->co = date('Y-m-d H:i:s');
                        $task->cb = 1;
                        $task->uo = date('Y-m-d H:i:s');
                        $task->ub = 0;
                        $task->status = 'on';
                        $task->mins =10;
                        if (isset($_POST['priority'])) {
                            $task->is_priority = 'yes';
                        } else {
                            $task->is_priority = 'no';
                        }
                        if (isset($_POST['deadlines']) && $_POST['deadlines'] != '') {
                            $task->due_dt = $this->resultDue($_POST['deadlines'], $_POST['due_date'], $fromTimeZone, $toTimeZone);
                            if ($_POST['deadlines'] == 'detail') {
                                $task->fuzzy ='none';
                            } else {
                                $task->fuzzy ='yes';
                            }
                        } //end if
                        $task->is_all = $_POST['is_all'];
                        $task->assignee_count = count($_POST['who']);
                        $task->rtype = 'none';
                        $task->rid = 1;
                        $task->n_id = 1;
                        // var_dump($task);die();
                        for ($i=0; $i <count($_POST['description']) ; $i++) {
                            $task_index = new Task();
                            $task_index->attributes = $task->attributes;
                            $task_index->description = $_POST['description'][$i];
                            if ($task_index->validate() && $task_index->save()) {
                                $taskUser = new TaskUser();
                                $taskUser->task_id = $task_index->id;
                                $taskUser->assigned_dt = $task_index->co;
                                $taskUser->completed_dt = '0000-00-00 00:00:00';
                                for ($j=0; $j < count($_POST['who']) ; $j++) {
                                    $taskUser_index = new TaskUser();
                                    $taskUser_index->attributes = $taskUser->attributes;
                                    $taskUser_index->user_id = $_POST['who'][$j];
                                    if (!$taskUser_index->validate() || !$taskUser_index->save()) {
                                        echo json_encode(['error' => 'Invalidate Task User']);
                                    }
                                }
                            }
                            else{
                                echo json_encode(['error' => 'Invalidate Task']);
                            }
                        }//en for
                    }
                } // end create
                if ($_POST['action'] == 'update') {
                    if (isset($_POST['task_id']) && $_POST['task_id'] != '') 
                    {
                        if (isset($_POST['description']) && (count($_POST['description']) > 0))
                        {
                            // $date = date('Y-m-d');
                            $task = new Task();
                            $task->co = date('Y-m-d H:i:s');
                            $task->cb = 1;
                            $task->uo = date('Y-m-d H:i:s');
                            $task->ub = 1;
                            $task->status = 'on';
                            $task->mins =10;
                            if (isset($_POST['priority'])) {
                                $task->is_priority = 'yes';
                            } else {
                                $task->is_priority = 'no';
                            }
                            if (isset($_POST['deadlines']) && $_POST['deadlines'] != '') {
                                // $a = new \DateTime('next sunday');   //$a = new \DateTime('now', new \DateTimeZone('UTC'));
                                // var_dump($this->convert(date('Y-m-d', strtotime('last day of this month', strtotime('next month', $now))).' '."23:59:59", 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone));die();
                                $task->due_dt = $this->resultDue($_POST['deadlines'], $_POST['due_date'], $fromTimeZone, $toTimeZone);
                                if ($_POST['deadlines'] == 'detail') {
                                    $task->fuzzy ='none';
                                } else
                                {
                                    $task->fuzzy ='yes';
                                }
                            } //end if
                            $task->is_all = $_POST['is_all'];
                            $task->assignee_count = count($_POST['who']);
                            $task->rtype = 'none';
                            $task->rid = 1;
                            $task->n_id = 1;
                            // update first task
                            $first_task = Task::find()->where('id=:id', [':id' => $_POST['task_id']])->one();
                            if ($first_task != null) {
                                $first_task->attributes = $task->attributes;
                                $first_task->description = $_POST['description'][0];
                                if ($first_task->validate() && $first_task->save()) {
                                    for ($u = 0; $u < count($_POST['who']) ; $u++) {
                                        $taskUser_u = TaskUser::find()->where('task_id=:task_id and user_id=:user_id', [':task_id' => $_POST['task_id'], ':user_id' => $_POST['who'][$u]])->one();
                                        if ($taskUser_u != null) {
                                            $taskUser_u->assigned_dt = $first_task->uo;
                                            $taskUser_u->completed_dt = '0000-00-00 00:00:00';
                                        }
                                        else {
                                            $taskUser_u = new TaskUser();
                                            $taskUser_u->task_id = $first_task->id;
                                            $taskUser_u->user_id = $_POST['who'][$u];
                                            $taskUser_u->assigned_dt = $first_task->co;
                                            $taskUser_u->completed_dt = '0000-00-00 00:00:00';
                                        }
                                        if (!$taskUser_u->validate() || !$taskUser_u->save()) {
                                            return json_encode(['error' => 'Invalidate Task User']);
                                        }
                                    }
                                } else {
                                    return json_encode(['error' => 'Invalidate Task']);
                                }
                            }
                            // var_dump($task);die();
                            //insert new task
                            for ($i=1; $i <count($_POST['description']) ; $i++) {
                                $task_index = new Task();
                                $task_index->attributes = $task->attributes;
                                $task_index->description = $_POST['description'][$i];
                                if ($task_index->validate() && $task_index->save()) {
                                    $taskUser = new TaskUser();
                                    $taskUser->task_id = $task_index->id;
                                    $taskUser->assigned_dt = $task_index->co;
                                    $taskUser->completed_dt = $task_index->due_dt;
                                    for ($j=0; $j < count($_POST['who']) ; $j++) {
                                        $taskUser_index = new TaskUser();
                                        $taskUser_index->attributes = $taskUser->attributes;
                                        $taskUser_index->user_id = $_POST['who'][$j];
                                        if (!$taskUser_index->validate() || !$taskUser_index->save()) {
                                            return json_encode(['error' => 'Invalidate Task User']);
                                        }
                                    }
                                }
                                else {
                                    return json_encode(['error' => 'Invalidate Task']);
                                }
                            } //en for
                        }
                    }
                } // end update
            }
            echo json_encode($this->renderData());
        }else{
            return $this->render('list', [
                'task' => Task::find()->where('status="on" OR status="off"')->orderBy('status, due_dt')->all(),
                'taskUser' =>TaskUser::find()->all(),
                ]);
        }
    }//end action list
    public function actionData_task()
    {
        if (Yii::$app->request->isAjax) {
            echo json_encode($this->renderData());
        }
    }
    /**
    * update status completed task
    */
    public function actionUpdate_status($id)
    {
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['status']) && $_POST['status'] != '') {
                $task = Task::find()->where('id=:id', [':id' => $id])->one();
                $taskUser_q = TaskUser::find()->where('task_id=:id', [':id' => $task->id]);
                $zeroDt =  '0000-00-00 00:00:00';
                $now = date('Y-m-d H:i:s');
                ///////////////////////////


                $forMe = false; // Task was assigned to me
                $taskCheckedByOne = false;
                $taskCheckedByAll = true;

                foreach ($taskUser_q->all() as $tu) {
                    if ($tu['user_id'] == $_POST['user_id']) {
                        $forMe = true;
                        // Revert check
                        $tu['completed_dt'] = $tu['completed_dt'] == $zeroDt ? $now : $zeroDt;
                        $sql= 'UPDATE at_task_user SET completed_dt=:completed_dt WHERE user_id=:user_id AND task_id=:task_id LIMIT 1';
                        Yii::$app->db->createCommand($sql, [
                            ':completed_dt'=>$tu['completed_dt'],
                            ':user_id'=>$_POST['user_id'],
                            ':task_id'=>$task['id'],
                            ])->execute();
                    }
                    if ($tu['completed_dt'] == $zeroDt) {
                        $taskCheckedByAll = false;
                    } else {
                        $taskCheckedByOne = true;
                    }
                }

                if ($forMe) {
                    if ($taskCheckedByAll || ($taskCheckedByOne && $task['is_all'] == 'no')) {
                        $sql = 'UPDATE at_tasks SET status="off" WHERE id=:id LIMIT 1';
                    } else {
                        $sql = 'UPDATE at_tasks SET status="on" WHERE id=:id LIMIT 1';
                    }
                    Yii::$app->db->createCommand($sql, [
                        ':id'=>$task['id'],
                        ])->execute();
                    echo json_encode($this->renderData());

                }
            }

            ///////////////////////////////////////
            // if ($_POST['status'] == 'off') {//     if ($task->is_all == 'no') {//         $task->status = 'off'; //         if ($task->save()) {//             $taskUser = $taskUser_q->andWhere('user_id=:user_id',[':user_id'=> $_POST['user_id']])->one(); //             if ($taskUser != null) {//                 $taskUser->completed_dt = date('Y-m-d H:i:s'); //                 if ($taskUser->save()) {//                     return json_encode($this->renderData()); //                 } //                 else {//                     return json_encode(['error' => 'can not save Task User']); //                 } //             } //             else {//                 return json_encode(['error' => 'can not found Task User']); //             } //         } //         else {//             return json_encode(['error' => 'can not save  the Task Status']); //         } //     }else{//         $state_completed = true; //         $taskUser = $taskUser_q->all(); //         foreach ($taskUser as  $tu) {//             if ($tu->user_id == $_POST['user_id']) {//                 $sql= 'UPDATE at_task_user SET completed_dt=:completed_dt WHERE user_id=:user_id AND task_id=:task_id LIMIT 1'; //                 Yii::$app->db->createCommand($sql, [//                     ':completed_dt'=>date('Y-m-d H:i:s'), //                     ':user_id'=> $tu->user_id, //                     ':task_id'=>$tu->task_id, //                 ])->execute(); //             } else {//                 if ($tu->completed_dt == '0000-00-00 00:00:00') {//                     $state_completed = false; //                 } //             } //         } //         if ($state_completed) {//             $task->status = 'off'; //             $task->save(); //         } //     } // } // else{//     if ($task->is_all == 'no') {//         $state_completed = false; //         $taskUser = $taskUser_q->all(); //         foreach ($taskUser as  $tu) {//             if ($tu->user_id == $_POST['user_id']) {//                 $sql= 'UPDATE at_task_user SET completed_dt=:completed_dt WHERE user_id=:user_id AND task_id=:task_id LIMIT 1'; //                 Yii::$app->db->createCommand($sql, [//                     ':completed_dt'=> '0000-00-00 00:00:00', //                     ':user_id'=> $_POST['user_id'], //                     ':task_id'=>$tu->task_id, //                 ])->execute(); //             } else {//                 if ($tu->completed_dt != '0000-00-00 00:00:00') {//                     $state_completed = true; //                 } //             } //         } //         if (!$state_completed) {//             $task->status = 'on'; //             $task->save(); //         } //     }else {//         $taskUser = $taskUser_q->andWhere('user_id=:user_id',[':user_id'=> $_POST['user_id']])->one(); //         if ($taskUser == null) {//             return json_encode(['error' => 'can not found  the Task User']); //         } //         $taskUser->completed_dt = '0000-00-00 00:00:00'; //         if ($taskUser->save()) {//             $task->status = 'on'; //             $task->save(); //         } //     } // }
            
        }
    }
    /**
    * Converts DT from TZ 1 to TZ 2
    * And formats DT
    */
    public static function convert($datetime, $format='d/m/Y', $fromTimeZone = 'UTC', $toTimeZone = 'Asia/Ho_Chi_Minh')
    {
        $dt = new \DateTime($datetime, new \DateTimeZone($fromTimeZone));
        $dt->setTimezone(new \DateTimeZone($toTimeZone));
        return $dt->format($format);
    }
    public function resultDue($deadline, $due_date, $fromTimeZone, $toTimeZone)
    {
        $date = date('Y-m-d');
        $now = strtotime("now");
        $day_index = date('w',$now);
        $num_day = 7-$day_index;
        switch ($_POST['deadlines']) {
        case 'today':
        return $this->convert($date.' '."23:59:59", 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
        break;

        case 'tomorrow':
        return $this->convert(date('Y-m-d', strtotime($date.' + 1 day'))." 23:59:59", 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
        break;

        case 'after_tomorrow':
        return $this->convert(date('Y-m-d', strtotime($date.' + 2 day')).' '."23:59:59", 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
        break;

        case 'this_week':
        return $this->convert(date('Y-m-d', strtotime($date.' + '.$num_day.' day')).' '."23:59:59", 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
        break;

        case 'next_week':
        return $this->convert(date('Y-m-d', strtotime('sunday',strtotime('next week'))).' '."23:59:59", 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
        break;

        case 'this_month':
        return $this->convert(date('Y-m-d', strtotime('last day of this month', $now)).' '."23:59:59", 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
        break;

        case 'next_month':
        return $this->convert(date('Y-m-d', strtotime('last day of this month', strtotime('next month', $now))).' '."23:59:59", 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
        break;

        case 'any_time':
        return $this->convert(date('Y-m-d', strtotime($date.' + 10 Year')).' '."23:59:59", 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
        break;

        case 'detail':
        return $this->convert($due_date, 'Y-m-d H:i:s', $fromTimeZone, $toTimeZone);
        break;
        } //en switch
    }
    function renderData(){
        $arr = [];
        $arr['task'] = Task::find()->where('status="on" OR status="off"')->orderBy('status, due_dt')->asArray()->all();
        $arr['taskUser'] = TaskUser::find()->asArray()->all();
        return $arr;
    }
}
