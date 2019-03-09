<?php
/**
 * Created by PhpStorm.
 * User: jackyang
 * Date: 2019-03-09
 * Time: 11:02
 */

namespace app\common\model;


class School extends BaseModel
{
    protected $name = 'school';
    protected $updateTime = false;



    /**
     * 获取所有学校
     * @return mixed
     */
    public static function getCacheAll()
    {
        return self::regionCache()['all'];
    }
}