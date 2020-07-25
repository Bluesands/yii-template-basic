<?php
namespace app\controllers\base;

use app\helpers\ArrayHelper;
use Yii;
use yii\web\Response;

/**
 * 全局入口控制器
 * @package app\controllers\base
 */
class Controller extends \yii\web\Controller
{
    public $layout = false;

    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        date_default_timezone_set('Asia/Shanghai');
        define('UPLOAD_MAX_SIZE', ini_get('upload_max_filesize'));

        define('RTC', Yii::$app->controller->id);
        define('RTM', Yii::$app->controller->action->id);

        define('IS_POST', Yii::$app->request->isPost);

        define('TIMESTAMP', time());
        define('DATETIME', date('Y-m-d H:i:s'));

        return true;
    }

    protected function post($key = null)
    {
        $post = ArrayHelper::underscoreKeys(Yii::$app->request->post());

        return $key ? $post[$key] : $post;
    }

    protected function get($key = null)
    {
        $get = ArrayHelper::underscoreKeys(Yii::$app->request->get());

        return $key ? $get[$key] : $get;
    }

    /**
     * --------------------------------------------------
     * 请求返回
     * --------------------------------------------------
     * @param int    $result 结果状态
     * @param string $msg    提示信息
     * @param array  $data
     * @return yii\web\Response
     */
    private function result(int $result, $msg, $data = null)
    {
        $ret = [
            'result' => $result,
            'msg'    => $msg,
            'data'   => ArrayHelper::camelizeKeys($data),
        ];

        $response         = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data   = $ret;

        return $response;
    }

    /**
     * --------------------------------------------------
     * 请求成功返回
     * --------------------------------------------------
     * @param array|object|string $msg
     * @param array               $data
     * @return yii\web\Response
     */
    public function success($msg = null, $data = null)
    {
        if (is_null($msg)) {
            $msg = '操作成功';
        } else {
            if (is_array($msg) || is_object($msg)) {
                $data = $msg;
                $msg  = '';
            }
        }

        return $this->result(1, $msg, $data);
    }

    /**
     * --------------------------------------------------
     * 请求失败返回
     * --------------------------------------------------
     * @param string $msg
     * @param array  $data
     * @return yii\web\Response
     */
    public function fail($msg, $data = [])
    {
        return $this->result(0, $msg, $data);
    }
}