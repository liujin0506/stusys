<?php
// 后台用户模块
class ZhuanyeAction extends CommonAction {
	function _filter(&$map){
		$map['zhuanyename'] = array('like',"%".$_POST['zhuanyename']."%");
	}
	// 检查帐号
	public function checkAccount() {
		$User = M("Zhuanye");
        // 检测用户名是否冲突
        $name  =  $_REQUEST['zhuanyename'];
        $result  =  $User->getByAccount($name);
        if($result) {
        	$this->error('该专业已经存在！');
        }else {
           	$this->success('该专业可以使用！');
        }
    }
    
	// 插入数据
	public function insert() {
		// 创建数据对象
		$_POST['create_time'] = time();
		$User	 =	 D("Zhuanye");
		if(!$User->create()) {
			$this->error($User->getError());
		}else{
			// 写入帐号数据
			if($result	 =	 $User->add()) {
				$this->success('专业添加成功！');
			}else{
				$this->error('专业添加失败！');
			}
		}
	}   
 
}
?>