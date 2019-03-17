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
     * @param   array    $columName    第一行的列名称
     * @param   array    $list         二维数组
     * @param   string   $setTitle    sheet名称
     * @return
     */
    function export_excel()
    {
        $model = new OrderModel;
        $list = $model->getAll([
            'pay_status' => 20,
            'delivery_status' => 10]);

        $list = $list->toArray();

//        var_dump($list);
        $name = '今日支付订单';
        vendor("PHPExcel.PHPExcel");
        $excel = new \PHPExcel(); //引用phpexcel
        iconv('UTF-8', 'gb2312', $name); //针对中文名转码
        $header= ['ID','名称','总价','重量','订单号',"付款时间","取货码","收货人","手机号","地址"]; //表头,名称可自定义
        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setTitle($name); //设置表名
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(30);

        $letter = ['A','B','C','D','E','F','G','H','I','J'];//列坐标
        //生成表头
        for($i=0;$i<count($header);$i++)
        {
            //设置表头值
            $excel->getActiveSheet()->setCellValue("$letter[$i]1",$header[$i]);
            //设置表头字体样式
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setName('宋体');
            //设置表头字体大小
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setSize(14);
            //设置表头字体是否加粗
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setBold(true);
            //设置表头文字水平居中
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //设置文字上下居中
            $excel->getActiveSheet()->getStyle($letter[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //设置单元格背景色
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->getStartColor()->setARGB('FFFFFFFF');
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->getStartColor()->setARGB('FF6DBA43');
            //设置字体颜色
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->getColor()->setARGB('FFFFFFFF');
        }
        if(is_array($list)){
            //写入数据
            foreach($list as $k=>$v)
            {
                //从第二行开始写入数据（第一行为表头）
                $excel->getActiveSheet()->setCellValue('A'.($k+2),$v['order_id']);
                $excel->getActiveSheet()->setCellValue('B'.($k+2),$v['goods'][0]['goods_name']);
                $excel->getActiveSheet()->setCellValue('C'.($k+2),$v['total_price']);
                $excel->getActiveSheet()->setCellValue('D'.($k+2),intval(""));
                //设置数字的科学计数法显示为文本
                $excel->getActiveSheet()->setCellValueExplicit('E'.($k+2),strval($v['order_no']),\PHPExcel_Cell_DataType::TYPE_STRING);
                $excel->getActiveSheet()->setCellValue('F'.($k+2),date("Y-m-d H:i:s",$v['pay_time']));
                $excel->getActiveSheet()->setCellValue('G'.($k+2),intval("089080980"));
                $excel->getActiveSheet()->setCellValue('H'.($k+2),$v['address']['name']);
                $excel->getActiveSheet()->setCellValue('I'.($k+2),$v['address']['phone']);
                $excel->getActiveSheet()->setCellValue('J'.($k+2),$v['address']['detail']);
            }
        }

    //设置单元格边框
    $excel->getActiveSheet()->getStyle("A1:J".(count($list)+1))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
    //清理缓冲区，避免中文乱码
    if (ob_get_contents()) ob_end_clean();
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$name.'.xls"');
    header('Cache-Control: max-age=0');
    //导出数据
    $res_excel = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $res_excel->save('php://output');
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
