<?php 

class foot {

    function __construct() {
        
    } 

    function get(){
       $this->show_page();
       return array(1,2,3);
    }

    function show_page(){
        location('index.php?/show_page/view');
    }   
	// 联系我们
    function show_page9_get(){
        
    	return array(1,2,3);
    }

    // 商场招商
    function join_us_get(){
        
    	return array(1,2,3);
    }

    // 购物指南
    function help_get(){
        
    	return array(1,2,3);
    }

    // 注册流程
    function show_page7_get(){
        
    	return array(1,2,3);
    }

    // 新手 FAQ
    function show_page11_get(){
        
    	return array(1,2,3);
    }

    // 纠纷处理规则
    function show_page4_get(){
        
    	return array(1,2,3);
    }

    // 商品退换规则
    function show_page5_get(){
        
    	return array(1,2,3);
    }
    
    // 我要投诉
    function tousu_get(){
        
    	return array(1,2,3);
    }


    // 公司信息
    function show_page_get(){

        $page=request('page');
        if ($page) {
          return $page;
        } 
          return page;    
        
    }

    // 公司信息-en
    function show_page_en_get(){
        
        return array(1,2,3);
    }

    // 公司信息-企业文化
    function show_page01_get(){
        
        return array(1,2,3);
    }

    // 公司信息-企业文化en
    function show_page01_en_get(){
        
        return array(1,2,3);
    }

     // 公司信息-商务合作
    function show_page8_get(){
        
        return array(1,2,3);
    }

    // 公司信息-商务合作en
    function show_page8_en_get(){
        
        return array(1,2,3);
    }

    // 公司信息-法律声明
    function show_page02_get(){
        
        return array(1,2,3);
    }

    // 公司信息-法律声明en
    function show_page02_en_get(){
        
        return array(1,2,3);
    }

    // 跨境电商综合税说明
    function tax_get(){
        return array(1,2,3);
    }

}    