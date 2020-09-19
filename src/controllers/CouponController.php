<?php

namespace modava\affiliate\controllers;

use backend\components\MyComponent;
use modava\affiliate\helpers\Utils;
use modava\affiliate\models\Customer;
use modava\affiliate\models\KolsFanForm;
use modava\affiliate\models\search\CustomerPartnerSearch;
use yii\db\Exception;
use Yii;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use modava\affiliate\AffiliateModule;
use backend\components\MyController;
use modava\affiliate\models\Coupon;
use modava\affiliate\models\search\CouponSearch;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends MyController
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
     * Lists all Coupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $kolsFanForm = new KolsFanForm();

        $totalPage = $this->getTotalPage($dataProvider);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalPage' => $totalPage,
            'kolsFanForm' => $kolsFanForm
        ]);
    }

    /**
     * Displays a single Coupon model.
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
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coupon();

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
     * Updates an existing Coupon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->quantity_used > 0) {
            Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-view', [
                'title' => 'Thông báo',
                'text' => 'Không thể sửa Coupon đã sử dụng',
                'type' => 'warning'
            ]);
            return $this->redirect(Url::toRoute(['view', 'id' => $id]));
        }

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
     * Deletes an existing Coupon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->quantity_used > 0) {
            Yii::$app->session->setFlash('toastr-' . $model->toastr_key . '-view', [
                'title' => 'Thông báo',
                'text' => 'Không thể xoá Coupon đã sử dụng',
                'type' => 'warning'
            ]);
            return $this->redirect(Url::toRoute(['view', 'id' => $id]));
        }

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

            $model = new Coupon();

            if ($id != null) $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionGenerateCode()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $customerId = Yii::$app->request->get('customer_id');
        $customerInfo = Customer::find()->where(['id' => $customerId])->one();

        if ($customerInfo) {
            $customerPartnerId = $customerInfo->partner_customer_id;
            $customerInfo = CustomerPartnerSearch::getCustomerById($customerPartnerId);

            if ($customerInfo) {
                $code = $customerInfo['customer_code'] . '_' . Utils::generateRandomString();
                return ['success' => true, 'data' => str_replace('-', '_', $code)];
            } else {
                return ['success' => false, 'message' => Yii::t('backend', 'Có lỗi xảy ra')];
            }
        }

        return ['success' => false, 'message' => Yii::t('backend', 'Không tìm thấy khách hàng')];
    }

    public function actionCheckCode()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $code = \Yii::$app->request->get('code');

        $coupon = Coupon::checkCoupon($code);

        if ($coupon) {
            return [
                'success' => true,
                'message' => Yii::t('backend', 'Mã code do khách hàng {full_name} giới thiệu', ['full_name' => $coupon->customer->full_name])
            ];
        }

        return [
            'success' => false,
            'message' => Yii::t('backend', 'Mã code không tồn tại hoặc đã được sử dụng')
        ];
    }

    public function actionSendSmsToCustomer($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Coupon::findOne($id);

        if ($model === null) {
            return [
                'success' => false,
                'message' => Yii::t('backend', 'Coupon không tồn tại')
            ];
        }

        if ($model->sendSmsToCustomer()) {
            return [
                'success' => true,
                'message' => Yii::t('backend', 'Gửi thành công')
            ];
        } else {
            $errors = Html::tag('p', Yii::t('backend', 'Gửi thất bại'));
            foreach ($model->getErrors() as $error) {
                $errors .= Html::tag('p', $error[0]);
            }
            return [
                'success' => false,
                'message' => $errors
            ];
        }
    }

    public function actionGetContentSmsCoupon() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $coupon = Coupon::findOne(Yii::$app->request->get('coupon_id'));

        if ($coupon === null) {
            return [
                'success' => false,
                'message' => Yii::t('backend', 'Coupon không tìm thấy')
            ];
        }

        return $coupon->getContentSmsCoupon('{ten}');
    }

    public function actionSendSmsCouponToFan() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new KolsFanForm();
        $model->load(Yii::$app->request->post());

        $coupon = Coupon::findOne($model->coupon_id);

        if (!$model->validate()) {
            $errors = Html::tag('p', Yii::t('backend', 'Gửi thất bại'));
            foreach ($model->getErrors() as $error) {
                $errors .= Html::tag('p', $error[0]);
            }
            return [
                'success' => false,
                'message' => $errors
            ];
        }

        if ($coupon === null) {
            return [
                'success' => false,
                'message' => Yii::t('backend', 'Coupon không tồn tại')
            ];
        }

        if ($coupon->sendSmsToFan($model->name, $model->phone)) {
            return [
                'success' => true,
                'message' => Yii::t('backend', 'Gửi thành công')
            ];
        } else {
            $errors = Html::tag('p', Yii::t('backend', 'Gửi thất bại'));
            foreach ($coupon->getErrors() as $error) {
                $errors .= Html::tag('p', $error[0]);
            }
            return [
                'success' => false,
                'message' => $errors
            ];
        }
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
     * Finds the Coupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */


    protected function findModel($id)
    {
        if (($model = Coupon::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
