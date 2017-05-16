$.fn.s_birthday = function(options) {
    var defaults = {
        year_num: 100,  //默认读取100年以内的年份
    };
    var parentBox = this;
    var opts = $.extend(defaults, options);
    var year = opts.year_num;
    var year_current = new Date().getFullYear(); //当前年份
    //创建年份
    function creatYear(year){
        for(var i =0;i<year;i++){
            var str ='<div class="year-item">'+(year_current-i)+'</div>';
            $('.select-year').append(str);
        }
    }
    //创建月份
    function creatMonth(){
        for(var i =1;i<13;i++){
            var str ='<div class="month-item">'+i+'</div>';
            $('.select-month').append(str);
        }
    }
    //创建天
    function creatDay(obj){
        if(obj=='default'){
            for(var i = 1;i<32;i++){
                var str ='<div class="day-item">'+i+'</div>';
                $('.select-day').append(str);
            }
        }else if(obj=='small'){
            for(var i = 1;i<31;i++){
                var str ='<div class="day-item">'+i+'</div>';
                $('.select-day').append(str);
            }
        }else if(obj=='run_true'){
            for(var i = 1;i<30;i++){
                var str ='<div class="day-item">'+i+'</div>';
                $('.select-day').append(str);
            }
        }else if(obj=='run_false'){
            for(var i = 1;i<29;i++){
                var str ='<div class="day-item">'+i+'</div>';
                $('.select-day').append(str);
            }
        }
    }

    //所有的选择事件
    parentBox.delegate('.selec>div','click',function(){
        $(this).addClass('active').parent().prev().text($(this).text());
        $(this).siblings().removeClass('active');
        $(this).parent().hide();
    })
    //月份选择事件
    parentBox.delegate('.select-month>div','click',function(){
        var months = $(this).text();
        if(months!=$('.select-month').prev().text()){
            $('.select-day').prev().text('请选择');
        }
        if(months=='4'||months=='6'||months=='9'||months=='11'){
            $('.select-day').empty();
            creatDay('small');
        }else if(months=='2'){
            var selectYear = $('.select-year').prev().text();//用户选择的年份
            if(selectYear!='请选择'){ //如果选择了年份才执行
                if((selectYear%4==0 &&selectYear%100!=0)||(selectYear%100==0 && selectYear%400==0)){//是闰年
                    $('.select-day').empty();
                    creatDay('run_true');
                }else{
                    $('.select-day').empty();
                    creatDay('run_false');
                }
            }
        }else{
            $('.select-day').empty();
            creatDay('default');
        }

    })
    $('.selected-active').click(function(e){
        //阻止冒泡
        e = e || window.event;
        if (e.stopPropagation) {
            e.stopPropagation();
        } else {
            e.cancelBubble = true;
        }
        $(this).parent().siblings().find('.selec').removeClass('show-se');
        $(this).next().toggleClass('show-se');
    })
    $(document).click(function(){
        $('.selec').removeClass('show-se');
    })
    creatYear(year);
    creatMonth();
    creatDay('default');
};
//初使化代码
$('.birthday').s_birthday({
    year_num: 80
});