$(document).ready(function(){
    function sendAjax(url,type="GET",data=null,dataType=null){
        return $.ajax({
            url:url,
            type:type,
            data:data,
            dataType:dataType
        });
    }

    //update Rate
    $("#submit").click(function(){
        var url = 'process/update-rate.php';
        var data = $('#update').serializeArray();
        var response = sendAjax(url,"POST",data);
        $("#error_data").fadeOut();
        response.success(function(data){
            data=JSON.parse(data);
            if(data.hasError)
                $("#error_data").html(data.msg).addClass('error').fadeIn();
            else{
                $("#price").val(data.price);
                $("#date").val(data.date);
                $("#error_data").html(data.msg).removeClass('error').fadeIn();
            }
        });
        response.error(function(req,err){
            $("#error_data").html("Something went wrong! Please try again later...").addClass('error').fadeIn();
        });
    });

    //getRate
    $('#car').on('change',function(){
        var url = 'process/getRate.php';
        var data = $('#update').serializeArray();
        var response = sendAjax(url,"POST",data);
        response.success(function(data){
            data=JSON.parse(data);
            if(data.hasError == 0){
                $("#date").val(data.date);
                $("#price").val(data.price);
            }
        });
        response.error(function(req,err){
            console.log(req);
        }); 
    });
});