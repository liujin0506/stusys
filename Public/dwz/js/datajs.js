function init(obj_1,val_1,obj_2,val_2,obj_3,val_3){
	
	//定义默认数据
	var ar = ["请选择省份","请选择城市","请选择区县"];
	var pindex=0;
	var cindex=0;
	
	//初始化
	$("<option value=''>"+ar[0]+"</option>").appendTo($("#"+obj_1));
	$("<option value=''>"+ar[1]+"</option>").appendTo($("#"+obj_2));
	$("<option value=''>"+ar[2]+"</option>").appendTo($("#"+obj_3));
	
	//初始化obj_1
	for (i=0;i<mp.length;i++){
			if (mp[i]==val_1){
				pindex = i;
				$("<option selected>"+mp[i]+"</option>").appendTo($("#"+obj_1));
			}else{
				$("<option value='"+mp[i]+"'>"+mp[i]+"</option>").appendTo($("#"+obj_1));
				}
		}

	if (pindex!=0){
			for (n=1;n<mc[pindex].length+1;n++){
					if (mc[pindex][n-1]==val_2){
						cindex = n;
						$("<option selected>"+mc[pindex][n-1]+"</option>").appendTo($("#"+obj_2));
					}else{						
						$("<option value='"+mc[pindex][n-1]+"'>"+mc[pindex][n-1]+"</option>").appendTo($("#"+obj_2));
					}			
				}		
		}


		
		
	//响应obj_1的change事件	
	$("#"+obj_1).change(function(){
		//获取索引
		pindex = $("#"+obj_1).get(0).selectedIndex;
		//清空c和h
		$("#"+obj_2).empty();
		//重新给c填充内容
		$("<option value=''>"+ar[1]+"</option>").appendTo($("#"+obj_2));
			if (pindex!=0){
				for (k=0;k<mc[pindex-1].length;k++){
					$("<option value='"+mc[pindex-1][k]+"'>"+mc[pindex-1][k]+"</option>").appendTo($("#"+obj_2));
				}
			}	
		//清空h
		$("#"+obj_3).empty();
		$("<option value='"+ar[2]+"'>"+ar[2]+"</option>").appendTo($("#"+obj_3));
	});
	
	//响应obj_2的change事件	
	$("#"+obj_2).change(function(){
		cindex = $("#"+obj_2).get(0).selectedIndex;
		
	});
	
}