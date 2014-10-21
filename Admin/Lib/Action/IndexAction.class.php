<?php
class IndexAction extends CommonAction {
	
	// 框架首页
	public function index() {

		if (isset ( $_SESSION [C ( 'USER_AUTH_KEY' )] )) {
			//luz start
			$volist=M("GroupClass")->where(array('status'=>1))->order("sort desc, id desc")->select();
			$this->volist=$volist;
			//luz end
			//显示菜单项
			$menu = array ();
			
			//读取数据库模块列表生成菜单项
			$node = M ( "Node" );
			$id = $node->getField ( "id" );
			$where ['level'] = 2;
			$where ['status'] = 1;
			$where ['pid'] = $id;
			$list = $node->where ( $where )->field ( 'id,name,group_id,title' )->order ( 'sort asc' )->select ();
			$accessList = $_SESSION ['_ACCESS_LIST'];
			foreach ( $list as $key => $module ) {
				if (isset ( $accessList [strtoupper ( APP_NAME )] [strtoupper ( $module ['name'] )] ) || $_SESSION ['administrator']) {
					//设置模块访问权限
					$module ['access'] = 1;
					//lxz 修改 获取当前分类的module
					$menu[$module['group_id']] [$key] =$module;
				}
			}
			
			if (! empty ( $_GET ['tag'] )) {
				$this->assign ( 'menuTag', $_GET ['tag'] );
			}

			//luz start
			$groups=M("Group")->where(array('group_menu'=>"{$volist[0]['menu']}",'status'=>"1"))->order("sort desc,id desc")->select();	
			$this->assign("groups",$groups);
			$articles=M("Article")->where(array('class_id'=>"3",'status'=>"1"))->order("sort desc,id desc")->select();	
			$this->assign("articles",$articles);
			//luz end
			$this->assign ( 'menu', $menu );
		}
		C ( 'SHOW_RUN_TIME', false ); // 运行时间显示
		C ( 'SHOW_PAGE_TRACE', false );
		
		$this->display ();
		
	}

}
?>