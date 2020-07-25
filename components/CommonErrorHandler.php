<?php

namespace app\components;

use YII;
use yii\web\ErrorHandler;
use yii\web\Response;

class CommonErrorHandler extends ErrorHandler
{
    protected function renderException($exception)
    {
        $statusCode    = $exception->statusCode;
        $code          = $exception->getCode();
        $messages      = $exception->getMessage();
        $file          = $exception->getFile();
        $line          = $exception->getLine();
        $previous      = $exception->getPrevious();
        $traceAsString = explode("\n#", $exception->getTraceAsString());

        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            $response->isSent  = false;
            $response->stream  = null;
            $response->data    = null;
            $response->content = null;
        } else {
            $response = new Response();
        }

        if (YII_ENV_PROD) {
            $ret = [
                'msg'    => $messages,
                'result' => 0,
                'data'   => $code,
                'code'   => $statusCode,
            ];
        } else {
            $ret = [
                'msg'           => $messages,
                'result'        => 0,
                'data'          => $code,
                'file'          => $file,
                'line'          => $line,
                'traceAsString' => $traceAsString,
                'previous'      => $previous,
                'code'          => $statusCode,
            ];
        }

        $response->data   = $ret;
        $response->format = \yii\web\Response::FORMAT_JSON;

        $response->send();
    }
}