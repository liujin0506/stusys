<?php
// 后台用户模块
class RleaveAction extends CommonAction
{
    function _filter(&$map)    {
        if ($_POST['date']) {
            $map['rdata1'] = array('elt',strtotime($_POST['date']));
            $map['rdata2'] = array('egt',strtotime($_POST['date']));
        }
    }
    // 检查帐号

    public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search ();
        if (method_exists ( $this, '_filter' )) {
            $this->_filter ( $map );
        }
        $map['sid'] = array('eq',$_SESSION[C('USER_AUTH_KEY')]);
        $name=$this->getActionName();
        $model = CM($name);
        if (! empty ( $model )) {
            $this->_list ( $model, $map );
        }
        $this->display ();
        return;
    }

    public function add(){
        $uesr = $_SESSION[C('USER_AUTH_KEY')];
        $userinfo = M("Student")->where('id='.$uesr)->find();
        $this->assign('userinfo',$userinfo);
        $this->display ();
    }

    public function edit(){
        $name=$this->getActionName();
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
        $this->assign ( 'vo', $vo );
        $uesr = $_SESSION[C('USER_AUTH_KEY')];
        $userinfo = M("Student")->where('id='.$uesr)->find();
        $this->assign('userinfo',$userinfo);
        $this->display ();
    }

    public function look(){
        $name=$this->getActionName();
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
        $this->assign ( 'vo', $vo );
        $uesr = $_SESSION[C('USER_AUTH_KEY')];
        $userinfo = M("Student")->where('id='.$uesr)->find();
        $this->assign('userinfo',$userinfo);
        $this->display ();
    }
    
    // 插入数据
    public function insert()
    {
        // 创建数据对象
        $_POST['createtime'] = time();
        $_POST['rdata1'] = strtotime($_POST['rdata1']);
        $_POST['rdata2'] = strtotime($_POST['rdata2']);
		$_POST['sid'] = $this->_post('uid');
		$_POST['createtime'] = time();
        $_POST['status'] = '0';
        $User = D("Rleave");
        if (!$User->create())
        {
            $this->error($User->getError());
        }
        else
        {
            // 写入帐号数据
            if ($result = $User->add())
            {
                $this->success('请假信息提交成功！');
            }
            else
            {
                $this->error('请假信息提交失败！');
            }
        }
    }

    public function update() {
        $_POST['rdata1'] = strtotime($_POST['rdata1']);
        $_POST['rdata2'] = strtotime($_POST['rdata2']);
        $name=$this->getActionName();
        $model = CM( $name );
        if (false === $model->create ()) {
            $this->error ( $model->getError () );
        }
        // 更新数据
        $list=$model->save ();
        if (false !== $list) {
            //成功提示
            $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
            $this->success ('编辑成功!');
        } else {
            //错误提示
            $this->error ('编辑失败!');
        }
    }
}
?>