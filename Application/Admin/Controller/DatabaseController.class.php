<?php
namespace Admin\Controller;
use Admin\Common\InitController;
/**
 * 数据库备份
 * @author DoCan Ravin
 */
class DatabaseController extends InitController {

    // 数据库备份信息列表
    public function index() {
        $dbbase = M('Database');
        $count = $dbbase->count();
        $Page = new \Think\Page($count, 15);
        $show = $Page->show();
        $list = $dbbase->order('addtime desc')
        ->limit($Page->firstRow . ',' . $Page->listRows)
        ->select();
        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->display();
    }

    // 数据库备份操作
    public function db() {
        // 配置信息
        $cfg_dbhost = C('DB_HOST');
        $cfg_dbname = C('DB_NAME');
        $cfg_dbuser = C('DB_USER');
        $cfg_dbpwd = C('DB_PWD');
        $cfg_db_language = 'utf8';
        // 统一一刻时间
        $time = time();
        // 文件路径以及文件名
        $path = './Public/data/back_up/';
        $file_name = date('Y-m-d', $time) . '-' . md5($time) . ".sql";
        $to_file_name = $path . $file_name;
        // 记录进数据库
        $data['name'] = $file_name;
        $data['addtime'] = $time;
        M('Database')->add($data);
        // END 配置
        // 链接数据库
        $link = mysql_connect($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd);
        mysql_select_db($cfg_dbname);
        // 选择编码
        mysql_query("set names " . $cfg_db_language);
        // 数据库中有哪些表
        $tables = mysql_list_tables($cfg_dbname);
        // 将这些表记录到一个数组
        $tabList = array();
        while ($row = mysql_fetch_row($tables)) {
            $tabList[] = $row[0];
        }
        $info = "-- ----------------------------\r\n";
        $info .= "-- 日期：" . date("Y-m-d H:i:s", $time) . "\r\n";
        $info .= "-- 不适合处理超大量数据\r\n";
        $info .= "-- ----------------------------\r\n\r\n";
        file_put_contents($to_file_name, $info, FILE_APPEND);
        // 将每个表的表结构导出到文件
        foreach ($tabList as $val) {
            $sql = "show create table " . $val;
            $res = mysql_query($sql, $link);
            $row = mysql_fetch_array($res);
            $info = "-- ----------------------------\r\n";
            $info .= "-- Table structure for `" . $val . "`\r\n";
            $info .= "-- ----------------------------\r\n";
            $info .= "DROP TABLE IF EXISTS `" . $val . "`;\r\n";
            $sqlStr = $info . $row[1] . ";\r\n\r\n";
            // 追加到文件
            file_put_contents($to_file_name, $sqlStr, FILE_APPEND);
            // 释放资源
            mysql_free_result($res);
        }
        // 将每个表的数据导出到文件
        foreach ($tabList as $val) {
            $sql = "select * from " . $val;
            $res = mysql_query($sql, $link);
            // 如果表中没有数据，则继续下一张表
            if (mysql_num_rows($res) < 1) {
                continue;
            }
            $info = "-- ----------------------------\r\n";
            $info .= "-- Records for `" . $val . "`\r\n";
            $info .= "-- ----------------------------\r\n";
            file_put_contents($to_file_name, $info, FILE_APPEND);
            // 读取数据
            while ($row = mysql_fetch_row($res)) {
                $sqlStr = "INSERT INTO `" . $val . "` VALUES (";
                foreach ($row as $zd) {
                    $sqlStr .= "'" . $zd . "', ";
                }
                // 去掉最后一个逗号和空格
                $sqlStr = substr($sqlStr, 0, strlen($sqlStr) - 2);
                $sqlStr .= ");\r\n";
                file_put_contents($to_file_name, $sqlStr, FILE_APPEND);
            }
            // 释放资源
            mysql_free_result($res);
            file_put_contents($to_file_name, "\r\n", FILE_APPEND);
        }
        $this->redirect('Database/index');
    }

    // 删除数据库备份
    public function delete(){
        $get = I('get.');
        if (IS_AJAX) {
            $get = I('get.');
            if (M('Database')->where('id=' . $get['id'])->delete()) {
                $path = './Public/data/back_up/' . $get['filename'];
                unlink($path);
                $result['status'] = true;
                $result['msg'] = '删除成功';
                $this->ajaxReturn($result);
            } else {
                $result['status'] = false;
                $result['msg'] = '非法操作';
                $this->ajaxReturn($result);
            }
        }
    }
}
