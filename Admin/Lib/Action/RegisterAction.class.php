<?php
// 后台用户模块
class RegisterAction extends CommonAction
{
    public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search ();
        if (method_exists ( $this, '_filter' )) {
            $this->_filter ( $map );
        }
        $name='Register_terms';
        $model = CM($name);
        if (! empty ( $model )) {
            $this->_list ( $model, $map );
        }
        $this->display ();
        return;
    }

    // 插入数据
    public function insert()
    {
        // 创建数据对象
        $_POST['createtime'] = time();
        $User = D("Register_terms");
        if (!$User->create())
        {
            $this->error($User->getError());
        }
        else
        {
            // 写入帐号数据
            if ($result = $User->add())
            {
                $students = M('Student')->where('status=1')->select();
                foreach ($students as $key => $value) {
                    $data['tid'] = $result;
                    $data['sid'] = $value['id'];
                    $data['status'] = '0';
                    M('Register_data')->data($data)->add();
                }
                $this->success('学期添加成功！');
            }
            else
            {
                $this->error('用学期添加失败！');
            }
        }
    }

    public function foreverdelete() {
        //删除指定记录
        $name='Register_terms';
        $model = CM($name);
        if (! empty ( $model )) {
            $pk = $model->getPk ();
            $id = $_REQUEST [$pk];
            if (isset ( $id )) {
                $condition = array ($pk => array ('in', explode ( ',', $id ) ) );
                if (false !== $model->where ( $condition )->delete ()) {
                    //echo $model->getlastsql();
                    $this->success ('删除成功！');
                } else {
                    $this->error ('删除失败！');
                }
            } else {
                $this->error ( '非法操作' );
            }
        }
        $this->forward ();
    }

    public function update() {
        $name='Register_terms';
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

    public function forbid() {
        $name='Register_terms';
        $model = CM($name);
        $pk = $model->getPk ();
        $id = $_REQUEST [$pk];
        $condition = array ($pk => array ('in', $id ) );
        $list=$model->forbid ( $condition );
        if ($list!==false) {
            $this->assign ( "jumpUrl", $this->getReturnUrl () );
            $this->success ( '状态禁用成功' );
        } else {
            $this->error  (  '状态禁用失败！' );
        }
    }

    function resume() {
        //恢复指定记录
        $name='Register_terms';
        $model = CM($name);
        $pk = $model->getPk ();
        $id = $_GET [$pk];
        $condition = array ($pk => array ('in', $id ) );
        if (false !== $model->resume ( $condition )) {
            $this->assign ( "jumpUrl", $this->getReturnUrl () );
            $this->success ( '状态恢复成功！' );
        } else {
            $this->error ( '状态恢复失败！' );
        }
    }

    public function forbidregister() {
        $name='Register_data';
        $model = CM($name);
        $pk = $model->getPk ();
        $id = $_REQUEST [$pk];
        $condition = array ($pk => array ('in', $id ) );
        $list=$model->forbid ( $condition );
        if ($list!==false) {
            $this->assign ( "jumpUrl", $this->getReturnUrl () );
            $this->success ( '取消注册成功' );
        } else {
            $this->error  ( '取消注册失败！' );
        }
    }

    public function resumeregister() {
        //恢复指定记录
        $name='Register_data';
        $model = CM($name);
        $pk = $model->getPk ();
        $id = $_GET [$pk];
        $condition = array ($pk => array ('in', $id ) );
        if (false !== $model->resume ( $condition )) {
            $model->where('id='.$id)->setField('registertime',time());
            $this->assign ( "jumpUrl", $this->getReturnUrl () );
            $this->success ( '注册成功！');
        } else {
            $this->error ( '注册失败！' );
        }
    }

    public function edit(){
        $name='Register_terms';
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
        $this->assign ( 'vo', $vo );
        $this->display ();
    }

    public function unregister(){
        $class = M("Classes")->where('status=1')->select();
        //dump($_POST);
        $sushe = M("Sushe")->where('status=1')->select();
        $name='Register_data';
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $map['tid'] = array('eq',$id);
        $tid = $id;
        $map['status'] = array('eq','0');
        if ($_POST['account'] ||$_POST['nickname'] || $_POST['class_id'] || $_POST['dormitory_id'] || $_POST['gender']) {
            if ($_POST['account']) {
                $map2['account'] = array('like', "%" . $_POST['account'] . "%");
            }
            if ($_POST['nickname']) {
                $map2['nickname'] = array('like', "%" . $_POST['nickname'] . "%");
            }
            if ($_POST['gender']) {
                $map2['gender'] = array('like',"%" . $_POST['gender'] . "%");
            }
            if ($_POST['class_id']) {
                $map2['class_id'] = array('eq',$_POST['class_id']);
            }
            if ($_POST['dormitory_id']) {
                $map2['dormitory_id'] = array('eq',$_POST['dormitory_id']);
            }
            $ids = M('Student') -> where($map2)->select();
            foreach ($ids as $key => $value) {
                $idss[] = $value['id'];
            }
            $map['sid'] = array('in',$idss);
            $map['tid'] = array('eq',$_POST['tid']);
            $tid = $_POST['tid'];
        }
        //dump($map);
        if (! empty ( $model )) {
            $this->_list ( $model, $map );
        }
        $this->assign('tid',$tid);
        $this->assign('classes',$class);
        $this->assign('sushees',$sushe);
        $this->display ();
    }

    public function register(){
        $class = M("Classes")->where('status=1')->select();
        //dump($_POST);
        $sushe = M("Sushe")->where('status=1')->select();
        $name='Register_data';
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $map['tid'] = array('eq',$id);
        $tid = $id;
        $map['status'] = array('eq','1');
        if ($_POST['account'] ||$_POST['nickname'] || $_POST['class_id'] || $_POST['dormitory_id'] || $_POST['gender']) {
            if ($_POST['account']) {
                $map2['account'] = array('like', "%" . $_POST['account'] . "%");
            }
            if ($_POST['nickname']) {
                $map2['nickname'] = array('like', "%" . $_POST['nickname'] . "%");
            }
            if ($_POST['gender']) {
                $map2['gender'] = array('like',"%" . $_POST['gender'] . "%");
            }
            if ($_POST['class_id']) {
                $map2['class_id'] = array('eq',$_POST['class_id']);
            }
            if ($_POST['dormitory_id']) {
                $map2['dormitory_id'] = array('eq',$_POST['dormitory_id']);
            }
            $ids = M('Student') -> where($map2)->select();
            foreach ($ids as $key => $value) {
                $idss[] = $value['id'];
            }
            $map['sid'] = array('in',$idss);
            $map['tid'] = array('eq',$_POST['tid']);
            $tid = $_POST['tid'];
        }
        //dump($map);
        if (! empty ( $model )) {
            $this->_list ( $model, $map );
        }
        $this->assign('tid',$tid);
        $this->assign('classes',$class);
        $this->assign('sushees',$sushe);
        $this->display ();
    }

    public function doregister(){
        $map['id'] = array('in',$_POST['ids']);
        $model = M ("Register_data");
        $vo = $model->where($map)->select();
        foreach ($vo as $key => $value) {
            $data['id'] = $value['id'];
            $data['status'] = '1';
            $model->data($data)->save();
        }
        $this->success('批量注册成功！');
    }

    public function dounregister(){
        $map['id'] = array('in',$_POST['ids']);
        $model = M ("Register_data");
        $vo = $model->where($map)->select();
        foreach ($vo as $key => $value) {
            $data['id'] = $value['id'];
            $data['status'] = '0';
            $model->data($data)->save();
        }
        $this->success('批量取消注册成功！');
    }

    public function sms_register(){
        $model = M ('Sms_mb');
        $mb = $model->where('model="register_register"')->find();
        if ($mb) {
            $map['id'] = array('in',$_POST['ids']);
            $model = M ("Register_data");
            $vo = $model->where($map)->select();
            if ($vo>getSmsNum()) {
                $this->error('您的短信剩余条数不足以完成本次发送，剩余条数'.getSmsNum());
            }else{
                foreach ($vo as $key => $value) {
                   $name = getStudentinfo($value['sid'],'nickname');
                   $xuehao = getStudentinfo($value['sid'],'account');
                   $class = getClass(getStudentinfo($value['sid'],'class_id'));
                   $gender = getStudentinfo($value['sid'],'gender');
                   $tel = getStudentinfo($value['sid'],'tel');
                   $content = str_replace('{name}', $name, $mb['content']);
                   $content = str_replace('{xuehao}', $xuehao, $content);
                   $content = str_replace('{class}', $class, $content);
                   $content = str_replace('{gender}', $gender, $content);
                   $content = str_replace('{tel}', $tel, $content);
                   sendSms($tel,$content);
                }
                $this->success('发送成功！');
            }
        }else{
            $this->error('请先设置模板');
        }
    }

    public function sms_unregister(){
        $model = M ('Sms_mb');
        $mb = $model->where('model="register_unregister"')->find();
        if ($mb) {
            $map['id'] = array('in',$_POST['ids']);
            $model = M ("Register_data");
            $vo = $model->where($map)->select();
            if ($vo>getSmsNum()) {
                $this->error('您的短信剩余条数不足以完成本次发送，剩余条数'.getSmsNum());
            }else{
                foreach ($vo as $key => $value) {
                   $name = getStudentinfo($value['sid'],'nickname');
                   $xuehao = getStudentinfo($value['sid'],'account');
                   $class = getClass(getStudentinfo($value['sid'],'class_id'));
                   $gender = getStudentinfo($value['sid'],'gender');
                   $tel = getStudentinfo($value['sid'],'tel');
                   $content = str_replace('{name}', $name, $mb['content']);
                   $content = str_replace('{xuehao}', $xuehao, $content);
                   $content = str_replace('{class}', $class, $content);
                   $content = str_replace('{gender}', $gender, $content);
                   $content = str_replace('{tel}', $tel, $content);
                   sendSms($tel,$content);
                }
                $this->success('发送成功！');
            }
        }else{
            $this->error('请先设置模板');
        }
    }

    public function Rleave(){
        $name='Register_data';
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
        $sinfo = M("Student")->getById ( $vo['sid'] );
        //dump($sinfo);
        $this->assign ( 'vo', $sinfo );
        $this->display ();
    }

    public function Rleaveinsert()
    {
        // 创建数据对象
        $_POST['sid'] = $_POST['sid'];
        $_POST['createtime'] = time();
        $_POST['rdata1'] = strtotime($_POST['rdata1']);
        $_POST['rdata2'] = strtotime($_POST['rdata2']);
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

    public function sms_mb_register(){
        $name="register_register";
        $model = M ('Sms_mb');
        $vo = $model->where('model="'.$name.'"')->find();
        if ($vo) {
            $this->assign ( 'vo', $vo );
        }
        if ($_POST) {
            $data['model'] = 'register_register';
            $data['content'] = $_POST['content'];
            $data['createtime'] = time();
            if (!$_POST['id']) {
                $model->add($data);
                $this->success('添加成功');
            }else{
                $model->where('id='.$_POST['id'])->save($data); 
                $this->success('修改成功');
            }
        }
        $this->display();
    }

    public function sms_mb_unregister(){
        $name="register_unregister";
        $model = M ('Sms_mb');
        $vo = $model->where('model="'.$name.'"')->find();
        if ($vo) {
            $this->assign ( 'vo', $vo );
        }
        if ($_POST) {
            $data['model'] = 'register_unregister';
            $data['content'] = $_POST['content'];
            $data['createtime'] = time();
            if (!$_POST['id']) {
                $model->add($data);
                $this->success('添加成功');
            }else{
                $model->where('id='.$_POST['id'])->save($data); 
                $this->success('修改成功');
            }
        }
        $this->display();
    }
}
?>