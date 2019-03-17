<?php
/**
 * Created by PhpStorm.
 * User: jackyang
 * Date: 2019-03-09
 * Time: 11:09
 */

namespace app\api\controller;

use app\api\model\UserSchool;
class School extends Controller
{
    /**
     * 学校列表
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        $model = new UserSchool;
        $list = $model->getList();
        return $this->renderSuccess([
            'list' => $list
        ]);
    }
}