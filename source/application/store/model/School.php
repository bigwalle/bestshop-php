<?php
/**
 * Created by PhpStorm.
 * User: jackyang
 * Date: 2019-03-09
 * Time: 14:25
 */

namespace app\store\model;

use app\common\model\School as UserSchool;
class School extends  UserSchool
{
    /**
     * 新增记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $data['wxapp_id'] = self::$wxapp_id;
        return $this->allowField(true)->save($data);
    }

    /**
     * 更新记录
     * @param $data
     * @return bool|int
     */
    public function edit($data)
    {
        return $this->allowField(true)->save($data) !== false;
    }

    /**
     * 删除记录
     * @return int
     */
    public function remove() {
        return $this->delete();
    }
}