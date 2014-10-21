<?php
// 后台学生基本信息模块
class StudentAction extends CommonAction
{
    function _filter(&$map)
    {
        if ($_POST['account']) {
            $map['account'] = array('like',"%" . $_POST['account'] . "%");
        }
		if ($_POST['sch_id']) {
            $map['sch_id'] = array('like',"%" . $_POST['sch_id'] . "%");
        }
        if ($_POST['nickname']) {
            $map['nickname'] = array('like',"%" . $_POST['nickname'] . "%");
        }
        if ($_POST['gender']) {
            $map['gender'] = array('like',"%" . $_POST['gender'] . "%");
        }
		 if ($_POST['ethnicity']) {
            $map['ethnicity'] = array('like',"%" . $_POST['ethnicity'] . "%");
        }
		if ($_POST['louhao']) {
            $map['louhao'] = array('like',"%" . $_POST['louhao'] . "%");
        }
		if ($_POST['sushe']) {
            $map['sushe'] = array('like',"%" . $_POST['sushe'] . "%");
        }
        if ($_POST['class_id']) {
            $map['class_id'] = array('eq',$_POST['class_id']);
        }
		if ($_POST['field']) {
            $map['field'] = array('eq',$_POST['field']);
        }
        if ($_POST['dormitory_id']) {
            $map['dormitory_id'] = array('eq',$_POST['dormitory_id']);
        }
        if ($_POST['poor_lever']) {
            $map['poor_lever'] = array('eq',$_POST['poor_lever']);
        }
		if ($_POST['politics']) {
            $map['politics'] = array('like',"%" . $_POST['politics'] . "%");
        }
		if ($_POST['grade']) {
            $map['grade'] = array('like',"%" . $_POST['grade'] . "%");
        }
		if ($_POST['type']) {
            $map['type'] = array('like',"%" . $_POST['type'] . "%");
        }
		if ($_POST['religion']) {
            $map['religion'] = array('like',"%" . $_POST['religion'] . "%");
        }
		if ($_POST['province']) {
            $map['province'] = array('like',"%" . $_POST['province'] . "%");
        }
		if ($_POST['city']) {
            $map['city'] = array('like',"%" . $_POST['city'] . "%");
        }
		if ($_POST['dist']) {
            $map['dist'] = array('like',"%" . $_POST['dist'] . "%");
        }
		if ($_POST['idcard']) {
            $map['idcard'] = array('like',"%" . $_POST['idcard'] . "%");
        }
		if ($_POST['tel']) {
            $map['tel'] = array('like',"%" . $_POST['tel'] . "%");
        }
		if ($_POST['cadres']) {
            $map['cadres'] = array('like',"%" . $_POST['cadres'] . "%");
        }
		if ($_POST['shetuan']) {
            $map['shetuan'] = array('like',"%" . $_POST['shetuan'] . "%");
        }
		if ($_POST['pici']) {
            $map['pici'] = array('like',"%" . $_POST['pici'] . "%");
        }
		if ($_POST['py_status']) {
            $map['py_status'] = array('like',"%" . $_POST['py_status'] . "%");
        }
    }

	
    public function sms(){
        $model = M ('Sms_mb');
        $mb = $model->where('model="Student"')->find();
        if ($mb) {
            $map['id'] = array('in',$_POST['ids']);
            $name=$this->getActionName();
            $model = M ( $name );
            $vo = $model->where($map)->select();
            if ($vo>getSmsNum()) {
                $this->error('您的短信剩余条数不足以完成本次发送，剩余条数'.getSmsNum());
            }else{
                foreach ($vo as $key => $value) {
                   $name = getStudentinfo($value['id'],'nickname');
                   $xuehao = getStudentinfo($value['id'],'account');
                   $class = getClass(getStudentinfo($value['id'],'class_id'));
                   $gender = getStudentinfo($value['id'],'gender');
                   $tel = getStudentinfo($value['id'],'tel');
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

    public function _before_index(){
		$zhuanye = M("Zhuanye")->where('status=1')->select();
        $class = M("Classes")->where('status=1')->select();
        $sushe = M("Sushe")->where('status=1')->select();
        $poor = M("Poor")->where('status=1')->select();
		$this->assign('zhuanye',$zhuanye);
        $this->assign('class',$class);
        $this->assign('sushe',$sushe);
        $this->assign('poor',$poor);
    }
    // 检查帐号
    public function checkAccount()
    {
        if (!preg_match('/^[a-z]\w{4,}$/i', $_POST['account']))
        {
            $this->error('用户名必须是字母，且5位以上！');
        }
        $User   = M("Student");
        // 检测用户名是否冲突
        $name   = $_REQUEST['account'];
        $result = $User->getByAccount($name);
        if ($result)
        {
            $this->error('该学号已经存在！');
        }
        else
        {
            $this->success('该学号可以使用！');
        }
    }

    public function add(){
	
		$zhuanye = M("Zhuanye")->where('status=1')->select();
        $class = M("Classes")->where('status=1')->select();
        $sushe = M("Sushe")->where('status=1')->select();
        $poor = M("Poor")->where('status=1')->select();
		$this->assign('zhuanye',$zhuanye);
        $this->assign('class',$class);
        $this->assign('sushe',$sushe);
        $this->assign('poor',$poor);
        $this->display ();
    }

    public function edit(){
        $name=$this->getActionName();
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
		$zhuanye = M("Zhuanye")->where('status=1')->select();
        $class = M("Classes")->where('status=1')->select();
        $sushe = M("Sushe")->where('status=1')->select();
        $poor = M("Poor")->where('status=1')->select();
        $this->assign ( 'vo', $vo );
		$this->assign('zhuanye',$zhuanye);
        $this->assign('class',$class);
        $this->assign('sushe',$sushe);
        $this->assign('poor',$poor);
        $this->display ();
    }
    
    // 插入数据
    public function insert()
    {
        // 创建数据对象
        //$_POST['password'] = md5(substr(str_replace('x', '', str_replace('X', '', $_POST['idcard'])), -6, 6));
		$_POST['password'] = md5('123456');
        $_POST['province'] = getProvince($_POST['province']);
        $_POST['city'] = getCity($_POST['city']);
        $_POST['dist'] = getDistrict($_POST['dist']);
        $_POST['create_time'] = time();
        $_POST['birthday'] = strtotime($_POST['birthday']);
        $User = D("Student");
        if (!$User->create())
        {
            $this->error($User->getError());
        }
        else
        {
            // 写入帐号数据
            if ($result = $User->add())
            {
                $this->addRole($result);
                $this->success('用户添加成功！');
            }
            else
            {
                $this->error('用户添加失败！');
            }
        }
    }

    public function update() {
        //B('FilterString');
        if (is_numeric($_POST['province'])) {
            $_POST['province'] = getProvince($_POST['province']);
            $_POST['city'] = getCity($_POST['city']);
            $_POST['dist'] = getDistrict($_POST['dist']);
        }
        $_POST['birthday'] = strtotime($_POST['birthday']);
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
    
    protected function addRole($userId)
    {
        //新增用户自动加入相应权限组
        $RoleUser          = M("RoleUser");
        $RoleUser->user_id = $userId;
        // 默认加入网站编辑组
        $RoleUser->role_id = 3;
        $RoleUser->add();
    }
    
    //重置密码
    public function resetPwd()
    {
        $id       = $_POST['id'];
        $password = $_POST['password'];
        if ('' == trim($password))
        {
            $this->error('密码不能为空！');
        }
        $User           = M('Student');
        $User->password = md5($password);
        $User->id       = $id;
        $result         = $User->save();
        if (false !== $result)
        {
            $this->success("密码修改为$password");
        }
        else
        {
            $this->error('重置密码失败！');
        }
    }
	
	

    public function importHandle()
    {
        if (IS_POST)
        {
            import('ORG.Net.UploadFile');
            $savePath = './Uploads/excels/'; //上传目录
            @chmod("$savePath", 0777); //设置目录可写
            $upload            = new UploadFile();
            $upload->maxSize   = 1000000; //上传附件大小,1M=1000000
            $upload->allowExts = array(
                'xls'
            ); //上传附件格式
            $upload->savePath  = $savePath; //上传目录,相对于单入口文件
            $upload->saveRule  = 'time'; //上传文件命名规则
            
            if ($upload->upload())
            {
                $info      = $upload->getUploadFileInfo();
                $file_name = $info[0]['savename']; //获取上传后的新文件名
            }
            else
            {
                $info = $upload->getErrorMsg();
                $this->error('文件上传失败，错误描述：' . $info);
            }
            /*
            
            *对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中
            
            注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入
            
            */
            $res = $this->read($savePath . $file_name);
            /*        
            重要代码 解决Thinkphp M、D方法不能调用的问题   
            如果在thinkphp中遇到M 、D方法失效时就加入下面一句代码    
            */
            //spl_autoload_register ( array ('Think', 'autoload' ) );
            /*对生成的数组进行数据库的写入*/
            foreach ($res as $k => $v)
            {
                if ($k != 0)
                {
                    $data['account']      = $v[0];
                    $data['sch_id']       = $v[1];
                    $data['password']     = md5('123456');
                    $data['nickname']     = $v[2];
                    $data['gender']       = $v[3];
                    $data['ethnicity']    = $v[4];
                    $data['birthday']     = $v[5];
                    $data['politics']     = $v[6];
                    $data['type']       = $v[7];
                    $data['field']         = $v[8];
                    $data['pici']        = $v[9];
                    $data['yeartype']         = $v[10];
                    $data['s_status']     = $v[11];
                    $data['grade']     = $v[12];
                    $data['class_id']     = $v[13];
                    $data['louhao'] = $v[14];
					$data['sushe'] = $v[15];
                    $data['poor_lever']   = $v[16];
                    $data['shetuan']      = $v[17];
                    $data['cadres']       = $v[18];
                    $data['tel']          = $v[19];
                    $data['idcard']       = $v[20];
                    $data['province']     = $v[21];
                    $data['city']         = $v[22];
                    $data['dist']         = $v[23];
                    $data['address']      = $v[24];
                    $data['home_tel']     = $v[25];
                    $data['f_name']       = $v[26];
                    $data['f_work']       = $v[27];
                    $data['f_tel']        = $v[28];
                    $data['m_name']       = $v[29];
                    $data['m_work']       = $v[30];
                    $data['m_tel']        = $v[31];
					$data['f_other']        = $v[32];
					$data['f_otherdw']        = $v[33];
					$data['f_othertel']        = $v[34];
                    $data['remark1']      = $v[35];
                    $data['remark2']      = $v[36];
                    $data['remark3']      = $v[37];
                    $data['create_time']  = time();
                    $data['update_time']  = time();
                    $data['status']       = '1';
                    $result               = M('Student')->add($data);
                    if (!$result)
                    {
                        $this->error('数据导入失败！');
                    }
                }
            }
            $this->success('数据导入成功!');
        }
    }
    //读取excel表
    public function read($filename, $encode = 'utf-8')
    {
        vendor('Excel.PHPExcel');
        $objReader = PHPExcel_IOFactory::createReader(Excel5);
        $objReader->setReadDataOnly(true);
        $objPHPExcel        = $objReader->load($filename);
        $objWorksheet       = $objPHPExcel->getActiveSheet();
        $highestRow         = $objWorksheet->getHighestRow();
        $highestColumn      = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData          = array();
        for ($row = 2; $row <= $highestRow; $row++)
        {
            for ($col = 0; $col < $highestColumnIndex; $col++)
            {
                $excelData[$row][] = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
    }

    public function uploadify()
 {
    if (!empty($_FILES)) {
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize = 2048000;
        $upload->allowExts = array('jpg','jpeg','gif','png');
        $upload->savePath = "./Uploads/images/";
        $upload->thumb = true; //设置缩略图
        $upload->imageClassPath = "ORG.Util.Image";
        $upload->thumbPrefix = "130_,75_"; //生成多张缩略图
        $upload->thumbMaxWidth = "130,75";
        $upload->thumbMaxHeight = "130,75";
        $upload->saveRule = uniqid; //上传规则
        $upload->thumbRemoveOrigin = true; //删除原图
        if(!$upload->upload()){
            $this->error($upload->getErrorMsg());//获取失败信息
        } else {
            $info = $upload->getUploadFileInfo();//获取成功信息
        }
        echo $info[0]['savename'];    //返回文件名给JS作回调用
    }
 }

   
}
?>