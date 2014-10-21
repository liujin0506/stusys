<?php
class GroupAction extends CommonAction {
    /**
     +----------------------------------------------------------
     * 默认排序操作
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws FcsException
     +----------------------------------------------------------
     */
    public function sort()
    {
		$node = M('Group');
        if(!empty($_GET['sortId'])) {
            $map = array();
            $map['status'] = 1;
            $map['id']   = array('in',$_GET['sortId']);
            $sortList   =   $node->where($map)->order('sort asc')->select();
        }else{
            $sortList   =   $node->where('status=1')->order('sort asc')->select();
        }
        $this->assign("sortList",$sortList);
        $this->display();
        return ;
    }


    public function index (){
        $groupClass=M("GroupClass")->where(array('status'=>1))->select();
        $array=array();
        foreach($groupClass as $val){
            $array[$val['menu']]=$val['name'];
        }
        $this->menu=$array;
        parent::index();
        
    } 
    public function add(){
        $this->groupClass=M("GroupClass")->where(array('status'=>1))->select();
        $this->display();
    }
    
    public function edit(){
        $this->groupClass=M("GroupClass")->where(array('status'=>1))->select();
        
        parent::edit();
    }

}
?>