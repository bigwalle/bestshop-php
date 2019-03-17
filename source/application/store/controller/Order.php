<?php

namespace app\store\controller;

use app\store\model\Order as OrderModel;

/**
 * 订单管理
 * Class Order
 * @package app\store\controller
 */
class Order extends Controller
{
    /**
     * 待发货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function delivery_list()
    {
        return $this->getList('待发货订单列表', [
            'pay_status' => 20,
            'delivery_status' => 10
        ]);
    }

    /**
     * 待收货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function receipt_list()
    {
        return $this->getList('待收货订单列表', [
            'pay_status' => 20,
            'delivery_status' => 20,
            'receipt_status' => 10
        ]);
    }

    /**
     * 待付款订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function pay_list()
    {
        return $this->getList('待付款订单列表', ['pay_status' => 10, 'order_status' => 10]);
    }

    /**
     * 导出excel表格
     *
     * @param   array    $columName    第一行的列名称
     * @param   array    $list         二维数组
     * @param   string   $setTitle    sheet名称
     * @return
     */
    function export_excel($columName, $list, $setTitle='Sheet1', $fileName='demo')
    {
        if ( empty($columName) || empty($list) ) {
            return '列名或者内容不能为空';
        }

        if ( count($list[0]) != count($columName) ) {
            return '列名跟数据的列不一致';
        }

        //实例化PHPExcel类
        $PHPExcel    =    new PHPExcel();
        //获得当前sheet对象
        $PHPSheet    =    $PHPExcel    ->    getActiveSheet();
        //定义sheet名称
        $PHPSheet    ->    setTitle($setTitle);

        //excel的列 这么多够用了吧？不够自个加 AA AB AC ……
        $letter        =    [
            'A','B','C','D','E','F','G','H','I','J','K','L','M',
            'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
        ];
        //把列名写入第1行 A1 B1 C1 ...
        for ($i=0; $i < count($list[0]); $i++) {
            //$letter[$i]1 = A1 B1 C1  $letter[$i] = 列1 列2 列3
            $PHPSheet->setCellValue("$letter[$i]1","$columName[$i]");
        }
        //内容第2行开始
        foreach ($list as $key => $val) {
            //array_values 把一维数组的键转为0 1 2 3 ..
            foreach (array_values($val) as $key2 => $val2) {
                //$letter[$key2].($key+2) = A2 B2 C2 ……
                $PHPSheet->setCellValue($letter[$key2].($key+2),$val2);
            }
        }
        //生成2007版本的xlsx
        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.$fileName.'.xlsx');
        header('Cache-Control: max-age=0');
        $PHPWriter->save("php://output");
    }

    /**
     * 已完成订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function complete_list()
    {
        return $this->getList('已完成订单列表', ['order_status' => 30]);
    }

    /**
     * 已取消订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function cancel_list()
    {
        return $this->getList('已取消订单列表', ['order_status' => 20]);
    }

    /**
     * 全部订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function all_list()
    {
        return $this->getList('全部订单列表');
    }

    /**
     * 订单列表
     * @param $title
     * @param $filter
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getList($title, $filter = [])
    {
        $model = new OrderModel;
        $list = $model->getList($filter);
        return $this->fetch('index', compact('title','list'));
    }

    /**
     * 订单详情
     * @param $order_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
        $detail = OrderModel::detail($order_id);
        return $this->fetch('detail', compact('detail'));
    }

    /**
     * 确认发货
     * @param $order_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delivery($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->delivery($this->postData('order'))) {
            return $this->renderSuccess('发货成功');
        }
        $error = $model->getError() ?: '发货失败';
        return $this->renderError($error);
    }

}
