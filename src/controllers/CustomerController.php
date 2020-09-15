<?php

namespace modava\affiliate\controllers;

use modava\affiliate\models\search\CustomerPartnerSearch;
use modava\affiliate\models\table\CustomerTable;
use yii\db\Exception;
use Yii;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use modava\affiliate\AffiliateModule;
use backend\components\MyController;
use modava\affiliate\models\Customer;
use modava\affiliate\models\search\CustomerSearch;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends MyController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-view', [
                    'title' => 'Thông báo',
                    'text' => 'Tạo mới thành công',
                    'type' => 'success'
                ]);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $errors = Html::tag('p', 'Tạo mới thất bại');
                foreach ($model->getErrors() as $error) {
                    $errors .= Html::tag('p', $error[0]);
                }
                Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-form', [
                    'title' => 'Thông báo',
                    'text' => $errors,
                    'type' => 'warning'
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-view', [
                        'title' => 'Thông báo',
                        'text' => 'Cập nhật thành công',
                        'type' => 'success'
                    ]);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                $errors = Html::tag('p', 'Cập nhật thất bại');
                foreach ($model->getErrors() as $error) {
                    $errors .= Html::tag('p', $error[0]);
                }
                Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-form', [
                    'title' => 'Thông báo',
                    'text' => $errors,
                    'type' => 'warning'
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        try {
            if ($model->delete()) {
                Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-index', [
                    'title' => 'Thông báo',
                    'text' => 'Xoá thành công',
                    'type' => 'success'
                ]);
            } else {
                $errors = Html::tag('p', 'Xoá thất bại');
                foreach ($model->getErrors() as $error) {
                    $errors .= Html::tag('p', $error[0]);
                }
                Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-index', [
                    'title' => 'Thông báo',
                    'text' => $errors,
                    'type' => 'warning'
                ]);
            }
        } catch (Exception $ex) {
            Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-index', [
                'title' => 'Thông báo',
                'text' => Html::tag('p', 'Xoá thất bại: ' . $ex->getMessage()),
                'type' => 'warning'
            ]);
        }
        return $this->redirect(['index']);
    }

    public function actionValidate($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $model = new Customer();

            if ($id != null) $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionGetInfo($phone = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            // Check customer trong hệ thống trước
            $customer = CustomerTable::getrecordByPhone($phone);
            $result = [
                'ho_ten' => null,
                'phu_trach' => null
            ];

            if ($customer !== null) {
                $result = [
                    'ho_ten' => $customer->full_name,
                    'phu_trach' => $customer->userCreated->userProfile->fullname
                ];
            } else {
                $customer = CustomerPartnerSearch::getCustomerByPhone($phone);

                if ($customer) {
                    $result = [
                        'ho_ten' => $customer['full_name'],
                        'phu_trach' => null
                    ];
                }
            }

            return $result;
        } catch (\yii\base\Exception $ex) {
            return [];
        }
    }

    public function actionGetCustomerByKeyWord($q = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $out = Customer::getCustomerByKeyWord($q);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Customer::findOne($id)->fullname];
        }
        return $out;
    }

    public function actionTotalCommission($id)
    {
        $data = Customer::getTotalRevenueByCustomer($id);
        $x_axis = array_map(function ($item) {
            return $item['created_at_y_m'];
        }, $data);
        $series = array_map(function ($item) {
            return $item['revenue'];
        }, $data);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'x_axis' => $x_axis,
            'series' => $series
        ];
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */


    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
