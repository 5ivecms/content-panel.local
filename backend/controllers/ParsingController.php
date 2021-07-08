<?php

namespace backend\controllers;

use common\models\Domain;
use Curl\Curl;
use Curl\MultiCurl;
use Yii;

class ParsingController extends AppController
{
    public function actionIndex()
    {
        $domains = Domain::find()->all();

        return $this->render('index', [
            'domains' => $domains
        ]);
    }

    public function actionStart()
    {
        $post = Yii::$app->request->post();
        if (!$post) {
            return false;
        }

        session_write_close();
        $start = microtime(true);
        $chunkSize = $post['chunk'];
        $requests = $post['requests'];
        $domainIds = $post['selection'];
        $chunkPause = (int)$post['chunk_pause'];

        if (!$domainIds) {
            return false;
        }

        $domains = Domain::find()->where(['id' => $domainIds])->asArray()->all();
        if (!$domains) {
            return false;
        }

        $domainsChunks = array_chunk($domains, $chunkSize);
        for ($i = 0; $i < $requests; $i++) {
            foreach ($domainsChunks as $domainsChunk) {
                $multiCurl = new MultiCurl();
                $multiCurl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
                $multiCurl->setTimeout(60);
                foreach ($domainsChunk as $domain) {
                    $curl = new Curl();
                    $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
                    $curl->setTimeout(60);
                    $curl->setHeader('Authorization', 'Bearer ' . $domain['token']);
                    $curl->get($domain['domain'] . Domain::PARSING_URL);
                    $multiCurl->addCurl($curl);
                }
                $multiCurl->start();
                $multiCurl->close();
                $multiCurl = []; unset($multiCurl);
                sleep($chunkPause);
            }
        }

        $finish = microtime(true);
        $delta = $finish - $start;

        Yii::$app->session->setFlash('info', 'Время выполнения ' . $delta . ' сек. Доменов параллельно ' . $chunkSize. ' Запросов ' . $requests. ' Пауза между чанками ' . $chunkPause . '.');

        return $this->redirect('index');
    }
}