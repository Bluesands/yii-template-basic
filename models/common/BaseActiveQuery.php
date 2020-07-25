<?php
namespace app\models\common;

use yii\data\Pagination;
use yii\db\ActiveQuery;

class BaseActiveQuery extends ActiveQuery
{
    /**
     * 重构条件查询
     * @param array $condition
     * @param array $params
     * @return BaseActiveQuery
     */
    public function where($condition, $params = [])
    {
        foreach ($condition as $k => $v) {
            if (is_null($v) || $v === '') {
                unset($condition[$k]);
            }
        }

        parent::where($condition, $params);

        return $this;
    }

    /**
     * @param int $pageSize
     * @return array
     */
    public function paginate($pageSize = 15)
    {
        $totalCount = $this->count();

        $pagination = new Pagination([
            'totalCount' => $totalCount,
            'pageSize'   => $pageSize,
        ]);

        $list = $this->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return [
            'list'  => $list,
            'pages' => [
                'total' => (int)$totalCount,
                'size'  => (int)$pageSize,
            ],
        ];
    }
}