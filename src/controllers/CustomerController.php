<?php

namespace modava\affiliate\controllers;

use backend\components\MyComponent;
use modava\affiliate\models\Order;
use modava\affiliate\models\search\CustomerPartnerSearch;
use modava\affiliate\models\table\CustomerTable;
use yii\data\ActiveDataProvider;
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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        $totalPage = $this->getTotalPage($dataProvider);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
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
            'orderDataProvider' => $dataProvider = new ActiveDataProvider([
                'query' => Order::getListOrderUsedCoupon($id),
                'pagination' => [
                    'pageSize' => 50,
                ],
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ])
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

    /**
     * @param $perpage
     */
    public function actionPerpage($perpage)
    {
        MyComponent::setCookies('pageSize', $perpage);
    }

    /**
     * @param $dataProvider
     * @return float|int
     */
    public function getTotalPage($dataProvider)
    {
        if (MyComponent::hasCookies('pageSize')) {
            $dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
        } else {
            $dataProvider->pagination->pageSize = 10;
        }

        $pageSize = $dataProvider->pagination->pageSize;
        $totalCount = $dataProvider->totalCount;
        $totalPage = (($totalCount + $pageSize - 1) / $pageSize);

        return $totalPage;
    }

    /**
     * @param null $id
     * @return array
     * @throws NotFoundHttpException
     */
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

    /**
     * @param null $phone
     * @return array
     */
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

    /**
     * @param null $q
     * @param null $id
     * @return array
     */
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

    /**
     * @return string
     */
    public function actionImportKols()
    {
        $path = Yii::getAlias('@modava/affiliate/templates/kols-export.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $data = $spreadsheet->getActiveSheet()->toArray();

        for ($i = 1; $i < count($data)-1; $i++) {
            $model = new Customer();
            $model->full_name = $data[$i][1];
            $model->description = "Code: " . $data[$i][2] . "\nĐường dẫn: " . $data[$i][3];
            $model->phone = 9999999000 + $i . '';
            $model->status = Customer::STATUS_HOAN_THANH_DICH_VU;
            $model->partner_id = 2;
            $model->save();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return 'Done';
    }

    /**
     * @return Order
     */
    public function actionGetListOrder() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $customerId = Yii::$app->request->get('customer_id');
        $listOrder = Order::getListOrderUsedCoupon($customerId);

        return $listOrder;
    }

    /**
     * @param $id
     * @return array
     */
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
