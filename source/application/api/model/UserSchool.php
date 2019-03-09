<?php
/**
 * Created by PhpStorm.
 * User: jackyang
 * Date: 2019-03-09
 * Time: 11:00
 */

namespace app\api\model;

use app\common\model\School as SchoolModel;
class UserSchool extends SchoolModel
{


    /**
     * 获取所有记录
     * @return UserSchool[]|false
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return self::all();
    }
}