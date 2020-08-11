<?php
namespace app\enums;

class Enum
{
    // 状态信息
    const STATUS_DELETED = -1;
    const STATUS_INVALID = 0;
    const STATUS_NORMAL = 1;
    const STATUS_VERIFYING = 9;

    // 状态信息文本
    const STATUS_TEXT = [
        self::STATUS_DELETED   => '删除',
        self::STATUS_INVALID   => '无效',
        self::STATUS_NORMAL    => '正常',
        self::STATUS_VERIFYING => '待审核',
    ];
}