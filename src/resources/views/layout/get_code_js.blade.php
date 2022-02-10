<script>
    {{--验证码倒计时--}}
    var countdown = function(configs){
        configs.start = /^\d+$/.test(configs.start) ? configs.start : 60;
        configs.counting = configs.counting||function(){};
        configs.finish = configs.finish||function(){};

        var start = parseInt(configs.start);

        var timer = setInterval(function(){
            start -= 1;
            if (start <= 0) {
                clearInterval(timer);
                configs.finish();
            } else {
                configs.counting(start);
            }
        },1000);

    };

    var loginSign = $("#loginSign").val()
    if($("#loginSign").val()){

        loginSign = loginSign;

    }else{
        loginSign = 1;
    }
    // 发送验证码
    $('#getCode').click(function(){
        $.ajax({
            url: url,
            type: "post",
            data:{
                phone:$("#phone").val(),
                type:type,
                loginSign:loginSign
            },
            dataType: 'json',
            success: function (data) {
                if(data.code == 1){
                    swal({
                        title: '验证码已发送成功',
                        text: "2秒后自动关闭。",
                        type: "success",
                        timer: 2000,
                        showConfirmButton: false,
                        closeModal: true
                    });
                    countdown({
                        start:data.result.time,
                        counting:function(number){
                            $(".code-button").html(number+'s后可重发');
                            $(".code-button").prop('disabled',true);
                        },
                        finish:function(){
                            $(".code-button").html('重新发送');
                            $(".code-button").prop('disabled',false);
                        }
                    });
                }else{
                    swal({
                        title: data.result,
                        text: "2秒后自动关闭。",
                        type: "warning",
                        timer: 2000,
                        showConfirmButton: false,
                        closeModal: true
                    });
                }
            }
        })
    });
</script>