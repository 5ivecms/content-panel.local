<?php

namespace backend\controllers;

use common\models\Domain;
use Curl\Curl;
use Yii;
use common\models\Config;
use common\models\ConfigSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ConfigController extends AppController
{

    public function actionIndex()
    {
        $searchModel = new ConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $domain = Domain::findOne($id);
        $settings = $this->loadSettings($domain);
        if (isset($settings['error'])) {
            return false;
        }

        $config = ArrayHelper::toArray($domain->config);
        unset($config['id'], $config['domain_id']);

        $config = $this->prepareConfig($config);
        $settings = $this->filterCronSettings($settings, $config);
        $settings = $this->prepareSettings($settings);
        $domain->config->load($settings, '');

        if ($domain->config->save()) {
            Yii::$app->session->setFlash('success', 'Домен ' . $domain->domain . ' успешно обновлен!');
        } else {
            Yii::$app->session->setFlash('danger', 'Ошибка. Домен не обновлен.');
        }

        return $this->redirect(['config/index']);
    }

    public function actionUpdateSelected()
    {
        if (!isset($_POST['ids'])) {
            return $this->asJson(false);
        }

        $messages = [];
        $domains = Domain::find()->where(['id' => $_POST['ids']])->all();
        foreach ($domains as $domain) {
            $settings = $this->loadSettings($domain);
            $config = ArrayHelper::toArray($domain->config);
            unset($config['id'], $config['domain_id']);
            $config = $this->prepareConfig($config);
            $settings = $this->filterCronSettings($settings, $config);
            $settings = $this->prepareSettings($settings);
            $domain->config->load($settings, '');
            if ($domain->config->save()) {
                $messages[] = 'Конфиг домена ' . $domain->domain . ' успешно обновлен!';
            } else {
                $messages[] = 'Ошибка. Конфиг домена ' . $domain->domain . ' не обновлен.';
            }
        }

        Yii::$app->session->setFlash('info', implode('<br>', $messages));

        return $this->redirect(['config/index']);
    }

    private function filterCronSettings($settings, $config)
    {
        $settings = ArrayHelper::toArray($settings);
        return array_filter($settings['items'], function ($item) use ($config) {
            return ArrayHelper::isIn($item['option'],$config);
        });
    }

    private function prepareSettings($settings)
    {
        $prepareSettings = [];
        foreach ($settings as $setting) {
            $prepareSettings[str_replace('.', '_', $setting['option'])] = $setting['value'];
        }

        return $prepareSettings;
    }

    private function loadSettings(Domain $domain)
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $curl->setHeader('Authorization', 'Bearer ' . $domain->token);
        $curl->get($domain->domain . Domain::SETTINGS_URL);

        if ($curl->error) {
            return ['error' => 'Error ' . $domain->domain . ': ' . $curl->errorCode . ': ' . $curl->errorMessage];
        }

        return (array) $curl->response;
    }

    protected function findModel($id)
    {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionEditable()
    {
        if (isset($_POST['hasEditable'])) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $index = $_POST['editableIndex'];
            $key = $_POST['editableKey'];
            $attr = $_POST['editableAttribute'];
            $value = $_POST['Config'][$index][$attr];

            $config = Config::findOne($key);
            $config->$attr = $value;
            if ($config->save()) {
                $result = $this->updateDomain($config->domain, [$attr, $value]);
                if (isset($result['status']) && $result['status'] == 'error') {
                    Yii::$app->session->setFlash('danger', 'Ошибка обновления. Домен ' . $result['domain']);
                    return ['output' => 'error', 'message' => 'Ошибка.'];
                }
            }

            return ['output' => $value, 'message' => ''];
        }

        return $this->redirect(['config/index']);
    }

    private function updateDomain(Domain $domain, $data)
    {
        $settings = $this->loadSettings($domain);

        $config = ArrayHelper::toArray($domain->config);
        unset($config['id'], $config['domain_id']);

        $config = $this->prepareConfig($config);
        $settings = $this->filterCronSettings($settings, $config);
        [$attr, $value] = $data;
        $optionArray = array_filter($settings, function ($item) use ($attr) {
            return $item['option'] === str_replace('_', '.', $attr);
        });

        $option = [];
        foreach ($optionArray as $item) {
            $option[] = $item;
        }

        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $curl->setHeader('Authorization', 'Bearer ' . $domain->token);
        $curl->put($domain->domain . Domain::SETTINGS_URL . '/' . $option[0]['id'], [
            'id' => $option[0]['id'],
            'value' => $value
        ]);

        if ($curl->error) {
            return ['error' => 'Error ' . $domain->domain . ': ' . $curl->errorCode . ': ' . $curl->errorMessage];
        }

        $curl->close();

        return (array) $curl->response;
    }

    private function prepareConfig($config)
    {
        $newConfig = [];
        foreach ($config as $k => $item) {
            $newKey = str_replace('_', '.', $k);
            $newConfig[$newKey] = $item;
        }

        return array_keys($newConfig);
    }
}
