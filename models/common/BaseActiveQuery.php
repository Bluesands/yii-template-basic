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

        $this->andWhere($condition, $params);

        return $this;
    }

    /**
     * 优化in查询
     * @param string $name
     * @param array  $value
     * @return BaseActiveQuery
     */
    public function andFilterIn(string $name, array $value)
    {
        $count = count($value);
        switch (true) {
            case $count == 0:
                break;
            case $count == 1:
                $this->andWhere([$name => $value[0]]);
                break;
            case $count >= 2:
                $this->andWhere([
                    'in',
                    $name,
                    $value,
                ]);
                break;
        }

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