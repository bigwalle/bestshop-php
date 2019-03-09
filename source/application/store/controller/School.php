<?php
/**
 * Created by PhpStorm.
 * User: jackyang
 * Date: 2019-03-09
 * Time: 14:24
 */

namespace app\store\controller;
use app\store\model\School as SchoolModel;
class School extends Controller
{
    /**
     * 学校列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new SchoolModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 添加商品
     * @return array|mixed
     */
    public function add()
    {
        $model = new SchoolModel;
        if (!$this->request->isAjax()) {
            return $this->fetch('add', compact('list'));
        }
        // 新增记录
        if ($model->add($this->postData('school'))) {
            return $this->renderSuccess('添加成功', url('school/index'));
        }
        $error = $model->getError() ?: '添加失败';
        return $this->renderError($error);
    }

    /**
     * 删除学校
     * @param $id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $model = SchoolModel::get($id);
        if (!$model->remove()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 编辑学校
     * @param $id
     * @throws \think\exception\DbException
     */
    public function edit($id)
    {
        // 学校详情
        $model = SchoolModel::get($id);
        if (!$this->request->isAjax()) {
            return $this->fetch('edit', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('school'))) {
            return $this->renderSuccess('更新成功', url('school/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }

}