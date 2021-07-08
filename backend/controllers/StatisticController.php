<?php

namespace backend\controllers;

use common\models\Domain;
use Curl\Curl;
use Yii;

class StatisticController extends AppController
{
    public function actionUpdate($id)
    {
        $id = (int) $id;
        $domain = Domain::findOne($id);

        $result = $this->loadStatistic($domain);
        if (isset($result['name']) && isset($result['message'])) {
            Yii::$app->session->setFlash('danger', $result['error']);
            return $this->redirect(['domain/index']);
        }

        $domain->statistic->load($result, '');
        if ($domain->statistic->save()) {
            Yii::$app->session->setFlash('success', 'Домен ' . $domain->domain . ' успешно обновлен!');
        } else {
            Yii::$app->session->setFlash('danger', 'Ошибка. Домен не обновлен.');
        }

        return $this->redirect(['domain/index']);
    }

    public function actionUpdateSelected()
    {
        if (!isset($_POST['ids'])) {
            return $this->asJson(false);
        }

        $messages = [];
        $domains = Domain::find()->where(['id' => $_POST['ids']])->all();
        foreach ($domains as $domain) {
            $result = $this->loadStatistic($domain);
            if (isset($result['name']) && isset($result['message'])) {
                $messages[] = $result['name'] . ' ' . $result['message'];
                continue;
            }

            $domain->statistic->load($result, '');
            if ($domain->statistic->save()) {
                $messages[] = 'Домен ' . $domain->domain . ' успешно обновлен!';
            } else {
                $messages[] = 'Ошибка. Домен ' . $domain->domain . ' не обновлен.';
            }
        }

        Yii::$app->session->setFlash('info', implode('<br>', $messages));

        return $this->redirect(['domain/index']);
    }

    public function actionUpdateAll()
    {
        $messages = [];
        $domains = Domain::find()->all();
        foreach ($domains as $domain) {
            $result = $this->loadStatistic($domain);
            if (isset($result['name']) && isset($result['message'])) {
                $messages[] = $result['name'] . ' ' . $result['message'];
                continue;
            }

            $domain->statistic->load($result, '');
            if ($domain->statistic->save()) {
                $messages[] = 'Домен ' . $domain->domain . ' успешно обновлен!';
            } else {
                $messages[] = 'Ошибка. Домен ' . $domain->domain . ' не обновлен.';
            }
        }

        Yii::$app->session->setFlash('info', implode('<br>', $messages));

        return $this->redirect(['domain/index']);
    }

    private function loadStatistic(Domain $domain)
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $curl->setHeader('Authorization', 'Bearer ' . $domain->token);
        $curl->get($domain->domain . Domain::GET_STATISTIC_URL);

        if ($curl->error) {
            return ['error' => 'Error ' . $domain->domain . ': ' . $curl->errorCode . ': ' . $curl->errorMessage];
        }

        return (array) $curl->response;
    }
}