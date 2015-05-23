/**
 * Created by vartan on 5/20/15.
 */
function switch_tab_1(){
    $.ajax({
        url: 'index.php',
        data:'todo=switch_tab_1',
        type:'POST',
        error: function(){
            //alert("load tab 1");
            //$('#wait').addClass("disp_none");
        },
        success:function(data){
            if($( "#tab1" ).hasClass( "display_none" )){
                $('#tab1').removeClass("display_none");
            }
            if(!$( "#tab2" ).hasClass( "display_none" )){
                $('#tab2').addClass("display_none");
            }
            if(!$( "#tab3" ).hasClass( "display_none" )){
                $('#tab3').addClass("display_none");
            }
            if(!$( "#tab4" ).hasClass( "display_none" )){
                $('#tab4').addClass("display_none");
            }
        }
    });
}
function switch_tab_2(){
    $.ajax({
        url: 'index.php',
        data:'todo=switch_tab_2',
        type:'POST',
        error: function(data){
            //alert("err : "+data);
            //$('#wait').addClass("disp_none");
        },
        success:function(data){
            if($( "#tab2" ).hasClass( "display_none" )){
                $('#tab2').removeClass("display_none");
            }
            if(!$( "#tab1" ).hasClass( "display_none" )){
                $('#tab1').addClass("display_none");
            }
            if(!$( "#tab3" ).hasClass( "display_none" )){
                $('#tab3').addClass("display_none");
            }
            if(!$( "#tab4" ).hasClass( "display_none" )){
                $('#tab4').addClass("display_none");
            }
        }
    });
}

function switch_tab_3(){
    $('#tab3').removeClass("display_none");
    $('#tab2').addClass("display_none");
    $('#tab1').addClass("display_none");
    $('#tab4').addClass("display_none");
    $.ajax({
        url: 'index.php',
        data:'todo=switch_tab_3',
        type:'POST',
        error: function(){
            //alert("load tab 1");
            //$('#wait').addClass("disp_none");
        },
        success:function(data){
            //$('#wait').addClass("disp_none");
        }
    });
}

function switch_tab_4(){
    $('#tab4').removeClass("display_none");
    $('#tab2').addClass("display_none");
    $('#tab3').addClass("display_none");
    $('#tab1').addClass("display_none");
    $.ajax({
        url: 'index.php',
        data:'todo=switch_tab_1',
        type:'POST',
        error: function(){
            //alert("load tab 1");
            //$('#wait').addClass("disp_none");
        },
        success:function(data){
            //$('#wait').addClass("disp_none");
        }
    });
}