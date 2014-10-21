<?php
class GroupClassAction extends CommonAction {
 
	public function purview(){
		$gcUser=M("GroupClassUser")->select();
		$array=array();
		foreach($gcUser as $v){
			$array[$v['gc_id']][]=$v['uid'];
		}
		$this->gcuser=$array;
		$userData=M("User")->where("id!=1")->select();
		$this->user=$userData;
	 	$this->display();
	}

	//保存权限
	public function gcUser(){
		$gcu=M("GroupClassUser")->where(array('gc_id'=>"{$_POST['gc_id']}"))->delete();
		foreach($_POST['uid'] as $val){
			$data['gc_id']=$_POST['gc_id'];
			$data['uid']=$val;
			M("GroupClassUser")->add($data);
		}
		
		$this->success('修改成功');
	}

}