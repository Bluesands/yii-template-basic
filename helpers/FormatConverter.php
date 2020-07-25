<?php
namespace app\helpers;

use yii\helpers\BaseFormatConverter;

class FormatConverter extends BaseFormatConverter
{
    /**
     * 格式化时间
     * @param string $datetime
     * @return string
     */
    public static function formatTime($datetime)
    {
        $ts = strtotime($datetime);

        // 今天凌晨的时间戳
        $todayTs = strtotime('today');
        // 昨天凌晨的时间戳
        $yesterdayTs = strtotime('yesterday');

        if ($ts > $todayTs) {
            $sub = TIMESTAMP - $ts;
            if ($sub == '0') {
                return '刚刚';
            } else {
                if ($sub < 60) {
                    return $sub . '秒前';
                } elseif ($sub < 3600) {
                    return floor($sub / 60) . '分钟前';
                } else {
                    return floor($sub / 3600) . '小时前';
                }
            }
        } elseif ($ts > $yesterdayTs) {
            return '昨天';
        }

        return substr($datetime, 5, 5);
    }

    public static function formatPeriod($t1, $t2)
    {
        return date_diff(date_create($t1), date_create($t2))->format('%d天%h小时%i分钟');
    }

    /**
     * 格式化数字
     * @param $num
     * @param $digits
     * @return string
     */
    public static function formatDecimal($num, $digits)
    {
        return round($num, $digits);
    }
}
