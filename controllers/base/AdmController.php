<?php
namespace app\controllers\base;

use Yii;

class AdmController extends Controller
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

        // 检测是否登录
        if (Yii::$app->adm->isGuest) {
            if (!in_array($action->uniqueID, $this->publicActions)) {
                echo json_encode(['result' => -1]);

                return false;
            }
        }

        return true;
    }
}
