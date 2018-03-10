/**
 * Created by Administrator on 2017/4/26.
 */

//获取玩家数据
var user = {};
$.ajax({
    type: "POST",
    url: "./app/get_user.php",
    data: {get_user:1},
    async: false,
    success: function(data){
        user = $.parseJSON(data);
    }
});
user.weight = parseFloat(user.weight);
user.gold = parseFloat(user.gold);
user.bamboo = parseFloat(user.bamboo);
user.land_level = parseInt(user.land_level);
user.subUser = parseInt(user.subUser);

var gameControl = {};
gameControl.cardWidth = 110;
gameControl.cardHeight = 60;
gameControl.land = user.land;
gameControl.zong =user.weight+user.gold;//
gameControl.tou = user.weight;//weight
gameControl.cang = user.gold;//gold
gameControl.liao = user.bamboo;//bamboo
gameControl.jiang = 0;
gameControl.yuan = 0;
gameControl.state = 0;
gameControl.chuan1 = 0;
gameControl.chuan2 = 0;
gameControl.num = 0;

gameControl.land_id = null;//当前操作的土地id
gameControl.open_reduce = user.open_reduce;//开发土地所需消耗200
gameControl.land_max_weight = user.land_max_weight;//土地上限1000

//对目前用户的土地初始化
function land_init() {
    for(var i=0;i<gameControl.land.length;i++){
        var land = gameControl.land[i];
        $('#lands').find('.'+land.land_id).addClass('newland'+user.land_level);
        $('#lands').find('.'+land.land_id).find('span').html(land.land_weight);
    }
}

function Kaifa(){
    $(".land").toggleClass("kai");
    $(".land").removeClass("tou");
    $(".land").removeClass("wei");
    $(".land").removeClass("shou");
    gameControl.state = 1;
}
function Futou(){
    $(".land").toggleClass("tou");
    $(".land").removeClass("kai");
    $(".land").removeClass("wei");
    $(".land").removeClass("shou");
    gameControl.state = 2;
    $(".land").each(function (){
        var a = Number($(this).find("span").html());
        if(a > 0 && a < gameControl.land_max_weight){
            $(this).attr("data-target","#myModal1");
        }
    })
}
function Changetou(){
    var tou = document.getElementById("changetou");
    var tou1 = gameControl.chuan1 + Number(tou.value);
    if(gameControl.cang >= Number(tou.value)){
        if(tou1 <= gameControl.land_max_weight){
            gameControl.tou = gameControl.tou + Number(tou.value);
            gameControl.cang = gameControl.cang - Number(tou.value);
            $("#showtou").html(gameControl.tou.toFixed(2));
            $("#showcang").html(gameControl.cang.toFixed(2));
            $("."+gameControl.num).find("span").html(""+tou1);
            $(".land").each(function (){
                $(this).removeAttr("data-target");
            })

            //同步数据 复投
            var tou_num = Number(tou.value);
            $.post('./app/game.php',{futou:1,tou_num:tou_num,land_id:gameControl.land_id},function (data) {
                data = $.parseJSON(data);
                if (data['code'] == 1){
                    alert("复投成功!");
                }else{
                    alert(data['msg']);
                }
            });

        }else{
            alert("超出上限!");
        }
    }else{
        alert("仓库剩余不足!");
    }
}
function Weiyang(){
    $(".land").toggleClass("wei");
    $(".land").removeClass("tou");
    $(".land").removeClass("shou");
    $(".land").removeClass("kai");
    gameControl.state = 3;
    $(".land").each(function (){
        var a = Number($(this).find("span").html());
        if(a > 0 && a < gameControl.land_max_weight){
            $(this).attr("data-target","#myModal2");
        }
    })
}
function Changewei(){
    var wei = document.getElementById("changewei");
    var wei1 = gameControl.chuan1 + Number(wei.value);
    if(gameControl.liao >= Number(wei.value)){
        if(wei1 <= gameControl.land_max_weight){
            gameControl.tou = gameControl.tou + Number(wei.value);
            gameControl.liao = gameControl.liao - Number(wei.value);
            $("#showtou").html(gameControl.tou.toFixed(2));
            $("#showliao").html(gameControl.liao.toFixed(2));
            $("."+gameControl.num).find("span").html(""+wei1);
            $(".land").each(function (){
                $(this).removeAttr("data-target");
            })

            //同步数据 喂食
            var tou_num = Number(wei.value);
            $.post('./app/game.php',{weishi:1,tou_num:tou_num,land_id:gameControl.land_id},function (data) {
                data = $.parseJSON(data);
                if (data['code'] == 1){
                    alert("喂食成功!");
                }else{
                    alert(data['msg']);
                }
            });

        }else{
            alert("超出上限!");
        }
    }else{
        alert("饲料剩余不足!");
    }
}
function Shouqu(){
    $(".land").toggleClass("shou");
    $(".land").removeClass("tou");
    $(".land").removeClass("wei");
    $(".land").removeClass("kai");
    gameControl.state = 4;
}
function Shengji(){
    // if(user.weight == 20*gameControl.land_max_weight){
        //升级请求
        $.post('./app/game.php',{up_level:1},function (data) {
            data = $.parseJSON(data);
            if (data['code'] == 1){
                alert("操作成功!");
                history.go(0);
            }else{
                alert(data['msg']);
            }
        });
    // }else{
    //     alert('土地未达到升级条件');
    // }
}
$(function(){
    var $land=$(".land");
    for(var i=0;i<19;i++)
    {
        $land.clone().appendTo($("#lands"));
    }
    $(".land").each(function(index){

        $(this).css({
            "left":(gameControl.cardWidth-50)*(index%4)+70*(index/4)+"px",
            "top":(gameControl.cardHeight-20)*Math.floor(index/4)-35*(index%4)+"px"
        })
        var num = index+1;
        $(this).attr("groundnum",num);
        $(this).addClass(""+num);
    })
    $(".land").click(function(){
        var land_id = $(this).attr('groundnum');
        gameControl.land_id = land_id;
        if(gameControl.state == 1){
            if(Number($(this).find("span").html()) == 0){
                if(gameControl.cang >= gameControl.open_reduce){
                    gameControl.cang = gameControl.cang - gameControl.open_reduce;
                    gameControl.tou = gameControl.tou + gameControl.open_reduce;
                    $(this).addClass("newland"+user.land_level);
                    $("#showtou").html(gameControl.tou);
                    $("#showcang").html(gameControl.cang);
                    $(this).find("span").html(gameControl.open_reduce);
                    $(".land").toggleClass("kai");

                    //同步数据
                    $.post('./app/game.php',{open:1,land_id:land_id},function (data) {
                        data = $.parseJSON(data);
                        if (data['code'] == 1){
                            alert("开地成功!");
                        }else{
                            alert(data['msg']);
                        }
                    });

                }else{
                    alert("仓库剩余不足"+gameControl.open_reduce+"!");
                }
            }else{
                alert("这块地开过了!");
            }
        }else if(gameControl.state == 2){
            if(Number($(this).find("span").html()) == 0) {
                alert("这块地还未开!");
            }else if(Number($(this).find("span").html()) == gameControl.land_max_weight){
                alert("已达本期上限,无法继续复投!")
            }else{
                gameControl.chuan1 = Number($(this).find("span").html());
                gameControl.num = $(this).attr("groundnum");
            }
            $(".land").toggleClass("tou");
        }
        else if(gameControl.state == 3){
            if(Number($(this).find("span").html()) == 0) {
                alert("这块地还未开!");
            }else if(Number($(this).find("span").html()) == gameControl.land_max_weight){
                alert("已达本期上限,无法继续喂食!")
            }else{
                gameControl.chuan1 = Number($(this).find("span").html());
                gameControl.num = $(this).attr("groundnum");
            }
            $(".land").toggleClass("wei");
        }else if(gameControl.state == 4){
            if(Number($(this).find("span").html()) == 0) {
                alert("这块地还未开!");
            }else{
                gameControl.cang = gameControl.cang + Number($(this).find("span").html()) - gameControl.open_reduce;
                gameControl.tou = gameControl.tou - Number($(this).find("span").html()) + gameControl.open_reduce;
                $("#showcang").html(gameControl.cang.toFixed(2));
                $("#showtou").html(gameControl.tou.toFixed(2));
                $(this).find("span").html(gameControl.open_reduce);

                //同步数据
                $.post('./app/game.php',{shouqu:1,land_id:land_id},function (data) {
                    data = $.parseJSON(data);
                    if (data['code'] == 1){
                        alert("收取!");
                    }else{
                        alert(data['msg']);
                    }
                });
            }
            $(".land").toggleClass("shou");
        }
        // window.history.go(0);
        //alert($(this).find("span").html());
    });
    if (user.subUser >= 30){
        gameControl.yuan = 3;
    }
    else if (user.subUser > 0){
        gameControl.yuan = Math.floor(user.subUser/10);
    }
    $("#showzong").html(gameControl.zong);
    $("#showtou").html(gameControl.tou.toFixed(2));
    $("#showcang").html(gameControl.cang.toFixed(2));
    $("#showliao").html(gameControl.liao.toFixed(2));
    $("#showjiang").html(gameControl.jiang);
    $("#showyuan").html(gameControl.yuan);

    land_init();
})