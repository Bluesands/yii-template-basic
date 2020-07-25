<?php
namespace app\controllers\base;

use Yii;

class WxminiController extends Controller
{
    /**
     * 免登录可访问资源
     * @var array
     */
    private $publicActions = [
    ];

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 检测授权
        if (Yii::$app->user->isGuest) {
            $isGuest = true;
            if (YII_ENV_DEV) {
                if (Yii::$app->params['devUser']['openid']) {
                    if (Yii::$app->user->loginByAccessToken(Yii::$app->params['devUser']['openid'])) {
                        $isGuest = false;
                    } else {
                        die('DEV_模拟登录用户数据错误');
                    }
                }
            }

            if ($isGuest && !in_array($action->uniqueID, $this->publicActions)) {
                echo json_encode(['result' => -1]);

                return false;
            }
        }

        return true;
    }
}