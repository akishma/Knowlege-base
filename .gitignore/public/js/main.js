 jQuery(document).ready(function() {
        $('.showup').css('display','none');
        $.ajaxSetup({
              headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });
        function get_clasters(id, type_for_adding){
            if(type_for_adding){
            var data={};
            data['id']=id;
            data['type']=type_for_adding;
            $.ajax({
                type: "POST",
                url: '/get_clasters',
                dataType: 'json',
                data: data,            
                success: function( data ) {
                    console.log(data);
                    $('#'+type_for_adding).empty();
                    $.each(data,function(key, value){
                        
                       var option=$('<option></option>').attr('value', key).text(value);
                       $('#'+type_for_adding).append(option); 
                    })
                }
                })
            }
            else{
                console.log(id);
            }

    }  

    $('#type').change(function() {
        var type_for_adding=$('#type').val();
    //    console.log( JSON.stringify(type_for_adding) );
        if(type_for_adding=='Main_claster'){            
            display_submit();
        }
        else{           
            $('#flow_claster').css('display', 'block');
            switch(type_for_adding) {
                case 'Claster': 
                change_name()
                $('#main_claster').attr('name', 'parent');
                      display_submit();
                      break;
                case 'Subject':  
                    get_clasters($('#main_claster').val(), type_for_adding);
                    change_name()
                    $('#claster').attr('name', 'parent');              
                    $('#flow_subject').css('display', 'block'); 
                    display_submit();                            
                    break;
                 case 'Feature':
                    change_name()
                    get_clasters($('#claster').val(),type_for_adding)
                    $('#subject').attr('name', 'parent');                   
                    $('#flow_subject').css('display', 'block');
                    $('#flow_feature').css('display', 'block');
                    display_submit();  
  
                    break;
             //   default:
             //       display_submit();
             //       break;
    
            }    
        }
    
    })
    function change_name(){
        var el_for_change=$( "select[name='parent']" );
        if(el_for_change.length>0){
            $( el_for_change[0] ).attr('name',el_for_change[0].id);
        }        
        console.log(el_for_change);
    }
    $('#main_claster').change(function(){
        get_clasters($('#main_claster').val(), 'claster'); 
    })
    $('#claster').change(function(){
        get_clasters($('#claster').val(), 'subject'); 
    })    
    


    function display_submit(){
        $('#flow_main_claster').css('display', 'block') ;  
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    $('.navigation').on("click",'.nav_button',function(){
             
       var data={};
       data['type']=$(this).parent().attr('id');
       data['table']=this.name;
       data['id']=this.value;
       data['name']=this.innerText;
       ajax_add_data(data);


    })   
        $('.main_container').on('click','.close_button',function(){
        console.log(this.id);
        $('#'+this.id).parent().parent().remove();
    }) 
            $('.main_container').on('click','.close_sub',function(){
        console.log(this.id);
        $('#'+this.id).parent().remove();
    }) 

    function ajax_add_data(data_type){ 
        console.log(data_type);
          $.ajax({
            type: "POST",
            url: '/ajax',
            dataType: 'json',
            data: data_type,            
            success: function( data ) {
                console.log(data);
                var child_div= $('<div></div>').attr('class', 'child_elem').attr('id','child_'+data_type['id']);
                var close_button=$("<button></button>").attr('id','close_button_'+data_type['id']).attr('class', 'close_sub').text('x');                 
                $(child_div).append(close_button);                                
                $.each(data['subject'],function(key, value){                
                    var button= $("<button></button>")
                        .attr("value",value['id'])
                        .attr('class','nav_button')          
                        .attr('type','button')
                        .text(value['name'])                        
                    $(child_div).append(button);  
                 
                });
                if(data_type['table']){
                    $('#child_'+data_type['id']).remove(); 
                    $('#claster_'+data_type['id']).after(child_div);   
                }

                $.each(data['parent'],function(key,value){
                  //  $('#'+data_type['type']+'_'+data_type['id']+'_'+key).remove();
                    $("div[id="+data_type['type']+'_'+data_type['id']+'_'+key+"]").remove();
                    $.each(value,function(child_key, child_value){
                        var topic=$('<h5></h5>').text(data_type['name']+' '+key);    
                        var data_div= $('<div></div>',{"class": "content clasters" });
                        $(data_div).attr('id',data_type['type']+'_'+data_type['id']+'_'+key);
                        var close_button=$("<button></button>").attr('id', 'close_'+key).attr('class', 'close_button').text('x');
                        var content_data_div=$('<div></div>',{"class": key });
                        var header_div=$('<div></div>').attr('class', 'topic');
                        
                        $(header_div).append(close_button);
                        $(header_div).append(topic);
                        $(data_div).append(header_div);
                        $(content_data_div).append(child_value['data'])                           
                                      
                        $(data_div).append(content_data_div); 
                        $('#main_menu').after(data_div);
                    })     
             }) 
              
            },
                error: function(data, textStatus, errorThrown){
                     console.log(data);
                 }
        });
    } 

    
    
    
 })