<?php
// 后台用户模块
class SusheAction extends CommonAction {
	function _filter(&$map){
		$map['sushe_id'] = array('like',"%".$_POST['sushe_id']."%");
	}
	// 检查帐号
	public function checkAccount() {
		$User = M("Sushe");
        // 检测用户名是否冲突
        $name  =  $_REQUEST['sushename'];
        $result  =  $User->getByAccount($name);
        if($result) {
        	$this->error('该宿舍已经存在！');
        }else {
           	$this->success('该宿舍可以使用！');
        }
    }
    
	// 插入数据
	public function insert() {
		// 创建数据对象
		$_POST['create_time'] = time();
		$User	 =	 D("Sushe");
		if(!$User->create()) {
			$this->error($User->getError());
		}else{
			// 写入帐号数据
			if($result	 =	 $User->add()) {
				$this->success('宿舍添加成功！');
			}else{
				$this->error('宿舍添加失败！');
			}
		}
	}   
 
}
?>