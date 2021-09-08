<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use frontend\models\Link;
use frontend\models\LinkSearchModel;
use frontend\forms\LinkForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Render list
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LinkSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Create item
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        // Init model
        $model = new Link(['scenario' => Link::SCENARIO_CREATE]);

        // Init form
        $formModel = new LinkForm();
        $formModel->setScenario(Link::SCENARIO_CREATE);
        $formModel->setModel($model);

        // Populate and reload form
        $status = false;
        if (Yii::$app->request->isPjax) {
            $formModel->load(Yii::$app->request->get());
        } elseif($formModel->load(Yii::$app->request->post()) && $formModel->save()) {
            $status = true;
        }

        return $this->asJson([
            'status' => $status,
            'form' => $this->renderAjax('_form', [
                'formModel' => $formModel,
            ]),
        ]);
    }

    /**
     * Go to url
     * @param string $hash
     * @throws NotFoundHttpException
     */
    public function actionGo($hash)
    {
        // Get model
        $link = $this->findModelByParams(Link::class, ['hash' => $hash]);

        // Check if it's relevant
        if (!$link->getIsRelevant()) {
            throw new NotFoundHttpException('The requested page does not exist');
        }

        // Update follows actions
        //

        // Go
        ///
    }

    /**
     * Finds the className model based on search params.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $className
     * @param mixed $searchParam
     * @return \yii\db\ActiveRecord the loaded model
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    protected function findModelByParams($className, $searchParam)
    {
        if (($model = $className::findOne($searchParam)) === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $model;
    }
}
