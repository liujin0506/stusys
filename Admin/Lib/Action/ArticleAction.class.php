<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luxingzhan
// +----------------------------------------------------------------------
// $Id: ArticleAction.class.php 1 2011-11-17 02:14:12Z luofei614@126.com $

class ArticleAction extends CommonAction{

	public function index(){
		$this->cate=C('NEWS_CATE');
		parent::index();
	}

	public function add(){
		$node=M("Node")->select();
		$nodeData=list_to_tree($node);
		$this->node=$nodeData;

		$groupId=$_GET['groupId'];
		$Access=M("Access")->where(array('role_id'=>"{$groupId}"))->select();

		$array=array();
		foreach($Access as $val){
			$array[$val['level']][]=$val['node_id'];
		}
		$this->selectdNode=$array;
		$this->display();
	}

	Public function insert() {
		$_POST['editor_id'] = $_SESSION[C('USER_AUTH_KEY')];
		$_POST['createtime'] = time();
		$_POST['status'] = '1';
		$name=$this->getActionName();
		$model = CM($name);
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		//保存当前数据对象
		$list=$model->add ();
		if ($list!==false) { //保存成功
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('新增成功!');
		} else {
			//失败提示
			$this->error ('新增失败!');
		}
	}

	public function search(){
		$this->display();
	}
}


