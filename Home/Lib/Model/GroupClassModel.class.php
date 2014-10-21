<?php
// 节点模型
class GroupClassModel extends CommonModel {
	protected $_validate	=	array(
		array('menu','checkMenu','menu已经存在',0,'callback'),
		);

	public function checkMenu() {
		$map['menu']	 =	 $_POST['menu'];
		$result	=	$this->where($map)->field('id')->find();
        if($result) {
        	return false;
        }else{
			return true;
		}
	}
}
?>