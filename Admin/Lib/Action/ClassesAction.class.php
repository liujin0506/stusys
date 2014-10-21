<?php
// 后台用户模块
class ClassesAction extends CommonAction {
	function _filter(&$map){
		$map['classname'] = array('like',"%".$_POST['classname']."%");
	}
	// 检查帐号
	public function checkAccount() {
		$User = M("Classes");
        // 检测用户名是否冲突
        $name  =  $_REQUEST['classname'];
        $result  =  $User->getByAccount($name);
        if($result) {
        	$this->error('该班级已经存在！');
        }else {
           	$this->success('该班级可以使用！');
        }
    }
    
	// 插入数据
	public function insert() {
		// 创建数据对象
		$_POST['create_time'] = time();
		$User	 =	 D("Classes");
		if(!$User->create()) {
			$this->error($User->getError());
		}else{
			// 写入帐号数据
			if($result	 =	 $User->add()) {
				$this->success('班级添加成功！');
			}else{
				$this->error('班级添加失败！');
			}
		}
	}   
 
}
?>