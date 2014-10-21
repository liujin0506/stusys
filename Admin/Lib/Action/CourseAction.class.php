<?php
// 课程管理模块
class CourseAction extends CommonAction {
	function _filter(&$map){
		$map['coursename'] = array('like',"%".$_POST['coursename']."%");
	}
    
	// 插入数据
	public function insert() {
		// 创建数据对象
		$_POST['create_time'] = time();
		$User	 =	 D("Course");
		if(!$User->create()) {
			$this->error($User->getError());
		}else{
			// 写入帐号数据
			if($result	 =	 $User->add()) {
				$this->success('课程添加成功！');
			}else{
				$this->error('课程添加失败！');
			}
		}
	}   
 
}
?>