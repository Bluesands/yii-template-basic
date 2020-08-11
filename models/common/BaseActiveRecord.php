<?php
namespace app\models\common;

use app\enums\Enum;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * 基础AR类 BaseActiveRecord
 * @property int    $id
 * @property int    $status
 * @property string $add_time
 * @property string $update_time
 */
class BaseActiveRecord extends ActiveRecord
{

    /**
     * @var string
     */
    public $err = '';

    /*
     * 基础rules
     */
    public function rules()
    {
        return [];
    }

    /**
     * $model->load() 不需要传第二个参数
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * 保存之前添加公共属性
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->isNewRecord) {
            // 新增记录
            $this->add_time    = $this->add_time ?? DATETIME;
            $this->update_time = $this->update_time ?? DATETIME;
            $this->status      = $this->status ?? Enum::STATUS_NORMAL;
        } else {
            // 更新记录
            $this->update_time = DATETIME;
        }

        return true;
    }

    /**
     * @return BaseActiveQuery
     */
    public static function find()
    {
        try {
            $q = Yii::createObject(BaseActiveQuery::class, [get_called_class()]);
            $q = $q->from(self::tableName() . ' a');

            return $q;
        } catch (InvalidConfigException $e) {
            return null;
        }
    }

    /**
     * @param array|string $where
     * @return BaseActiveRecord|null
     */
    public static function one($where)
    {
        return empty($where) ? new static() : self::findOne($where);
    }

    /**
     * 获取错误信息
     * @return bool|mixed
     */
    public function getFirstErrorMessage()
    {
        $error = $this->getFirstErrors();
        if (!empty($error['error'])) {
            return false;
        }

        return current($error);
    }

    /**
     * @param array $where
     * @return int|string
     */
    public static function count(array $where = [])
    {
        return static::find()
            ->where($where)
            ->count();
    }

    /**
     * 查询列表, 自带分页
     * @param array | null $fields 查询字段
     * @param array|null   $where  查询条件
     * @param array|null   $order  查询排序
     * @param int|null     $page   每页数
     * @return array|ActiveRecord[]
     */
    public function findList(array $fields = null, array $where = null, array $order = null, int $page = 15)
    {
        $fields = is_null($fields) ? '*' : $fields;
        $where  = is_null($where) ? ['status' => Enum::STATUS_NORMAL] : $where;
        $order  = is_null($order) ? ['id' => SORT_DESC] : $order;

        return $this->find()
            ->select($fields)
            ->where($where)
            ->orderBy($order)
            ->paginate($page);
    }

    /** 获取全部数据
     * @param array|null $fields
     * @param array|null $where
     * @param array|null $order
     * @return array|ActiveRecord[]
     */
    public function findAllList(array $fields = null, array $where = null, array $order = null)
    {
        $fields = is_null($fields) ? '*' : $fields;
        $where  = is_null($where) ? ['status' => Enum::STATUS_NORMAL] : $where;
        $order  = is_null($order) ? ['id' => SORT_DESC] : $order;

        return $this->find()
            ->select($fields)
            ->where($where)
            ->orderBy($order)
            ->asArray()
            ->all();
    }

    /**
     * 根据id查找模型
     * @param int $id
     * @return BaseActiveRecord|null
     */
    public function findById(int $id)
    {
        return self::findOne($id);
    }

    /**
     * 删除记录
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id)
    {
        $model         = self::findOne($id);
        $model->status = Enum::STATUS_NORMAL;

        return $model->save();
    }

    /**
     * 新增记录
     * @param array $params
     * @return bool
     */
    public function add(array $params)
    {
        return ($this->load($params) && $this->save());
    }

    /**
     * 编辑
     * @param int   $id
     * @param array $params
     * @return bool
     */
    public function edit(int $id, array $params)
    {
        $model = self::findOne($id);

        return ($model->load($params) && $model->save());
    }

    /**
     * 搜索查询
     * @param string     $keyword      关键字
     * @param array|null $searchFields 搜索字段
     * @param array|null $fields       查询字段
     * @param array|null $where        查询条件
     * @param array|null $order        查询排序
     * @return array|ActiveRecord[]
     */
    public function search(
        string $keyword,
        array $searchFields = null,
        array $fields = null,
        array $where = null,
        array $order = null
    ) {
        $fields = is_null($fields) ? '*' : $fields;
        $where  = is_null($where) ? ['status' => Enum::STATUS_NORMAL] : $where;
        $order  = is_null($order) ? ['id' => SORT_DESC] : $order;

        if (is_null($searchFields) || empty($keyword)) {
            return $this->findAllList();
        }

        $query = $this->find()
            ->select($fields)
            ->where($where);

        foreach ($searchFields as $field) {
            $query->andFilterWhere([
                'like',
                $field,
                $keyword,
            ]);
        }

        return $query->orderBy($order)
            ->asArray()
            ->all();
    }
}