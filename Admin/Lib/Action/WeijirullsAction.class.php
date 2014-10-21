<?php
// 后台用户模块
class WeijirullsAction extends CommonAction {
	function _filter(&$map){
		$map['name'] = array('like',"%".$_POST['name']."%");
	}
	// 检查帐号
	public function checkAccount() {
		$User = M("Weijirulls");
        // 检测用户名是否冲突
        $name  =  $_REQUEST['name'];
        $result  =  $User->getByAccount($name);
        if($result) {
        	$this->error('该项目已经存在！');
        }else {
           	$this->success('该项目可以使用！');
        }
    }
    
	// 插入数据
	public function insert() {
		// 创建数据对象
		$_POST['create_time'] = time();
		$User	 =	 D("Weijirulls");
		if(!$User->create()) {
			$this->error($User->getError());
		}else{
			// 写入帐号数据
			if($result	 =	 $User->add()) {
				$this->success('项目添加成功！');
			}else{
				$this->error('项目添加失败！');
			}
		}
	}   
 
}
?>