<?php
/**
 * Created by PhpStorm.
 * User: jackyang
 * Date: 2019-03-09
 * Time: 11:02
 */

namespace app\common\model;

use think\Request;
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

    /**
     * 获取学校列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        $request = Request::instance();
        return $this->order(['id' => 'desc'])
            ->paginate(15, false, ['query' => $request->request()]);
    }

}