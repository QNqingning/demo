<?php
namespace app\admin\controller;
use think\Controller;
use Think\Db;
use think\Request;

class Course extends Lock{
	public function index(){	
		
		$list = db("course")->select();	
		$total = db("course")->count();
//		print_r($list);	
		$this->assign("list",$list);	
		$this->assign("total",$total);	
		
		return view();
	}
	
	
	//上传文件
	public function excel(){
		if(request() -> isPost())
        {
            vendor("PHPExcel.PHPExcel"); 
            $objPHPExcel =new \PHPExcel();
            //获取表单上传文件
            $file = request()->file('excel');
            $info = $file->validate(['ext' => 'xlsx'])->move(ROOT_PATH . 'public'.DS.'uploads');  //上传验证后缀名,以及上传之后移动的地址  E:\wamp\www\cls\public
            if($info)
            {
//              echo $info->getFilename();
                $exclePath = $info->getSaveName();  //获取文件名
                $file_name = ROOT_PATH . 'public'.DS.'uploads' . DS . $exclePath;//上传文件的地址
                $objReader =\PHPExcel_IOFactory::createReader("Excel2007");
                $obj_PHPExcel =$objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
                $excel_array=$obj_PHPExcel->getSheet(0)->toArray();   //转换为数组格式
                array_shift($excel_array);  //删除第一个数组(标题);
                $city = [];
                $i=0;
                
                foreach($excel_array as $k=>$v) {
                    $city[$k]['f1'] = $v[0];
                    $city[$k]['f2'] = $v[1];
                    $city[$k]['f3'] = $v[2];
                    $city[$k]['f4'] = $v[3];
                    $city[$k]['f5'] = $v[4];
                    $city[$k]['f6'] = $v[5];
                    $city[$k]['f7'] = $v[6];
                    $city[$k]['f8'] = $v[7];
                    $city[$k]['f9'] = $v[8];
                    $city[$k]['f10'] = $v[9];
                    $city[$k]['f11'] = $v[10];
                    $city[$k]['f12'] = $v[11];
                    $city[$k]['f13'] = $v[12];
                    $city[$k]['f14'] = $v[13];
                    $city[$k]['f15'] = $v[14];
                    $city[$k]['f16'] = $v[15];
                    $city[$k]['f17'] = $v[16];
                    $city[$k]['f18'] = $v[17];
                    $city[$k]['f19'] = $v[18];
                    $city[$k]['f20'] = $v[19];
                    $city[$k]['f21'] = $v[20];
                    $city[$k]['f22'] = $v[21];
                    $city[$k]['f23'] = $v[22];
                    $city[$k]['f24'] = $v[23];
                    $city[$k]['f25'] = $v[24];
                    $city[$k]['f26'] = $v[25];
                    $city[$k]['f27'] = $v[26];
                    $city[$k]['f28'] = $v[27];
                    $city[$k]['f29'] = $v[28];
                    $city[$k]['f30'] = $v[29];
                    $city[$k]['f31'] = $v[30];
                    $i++;
                }
                db("course")->insertAll($city);
                $this->redirect('Course/index');
            }else
            {
                echo $file->getError();
            }
        }
	}
	
	
	public function message(){
		$major = $_POST['major'];
		$day = $_POST['day'];
		$lesson = $_POST['lesson'];
		$message = $_POST['message'];
		
		//声明一个数组，用来保存要插入的数据
		$arr = array();
		
		for($i=0; $i<count($major); $i++){
			if($day[$i]==''){
				$day[$i]=$day[$i-1];
			}		
			$arr[] = [
				"major" => $major[$i],
				"day" => $day[$i],
				"lesson" => $lesson[$i],
				"message" => $message[$i],
			];
					
		}
		$data = db('syllabus')->select();
		if(!$data){
			//批量插入数据用inserAll，insert有限制。$arr要是数组
			db('syllabus')->insertAll($arr);
			$this->redirect("Course/syllabus");
		}
		else{
			$this->error('数据已存在');
		}
	}
	
	public function syllabus(){
		
		$search = input("get.search");
		$list = db("syllabus")->where("message like '%$search%'")->select();	
		$total = db("syllabus")->where("message like '%$search%'")->count();
		
//		var_dump($list);
//		exit;
		$this->assign("list",$list);
		$this->assign("total",$total);	
		return view();	
	}
	
	public function room_course(){
		//获取搜索框的内容
		$search = input("get.search");
		$data = db("syllabus")->where("message like '%$search%'")->select();
//		var_dump($data);
	}

}
?>