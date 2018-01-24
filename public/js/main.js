 jQuery(document).ready(function() {
        $('#description').summernote({
          //    height:300,
          
              minHeight: null,             // set minimum height of editor
              maxHeight: null,             // set maximum height of editor
              focus: true ,
                toolbar: [    
    ['style', ['bold', 'italic', 'underline', 'clear']],    
    ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    
  ]
               })
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
       //     data['type']=type_for_adding;
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
                       $('#claster').append(option); 
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
        $('#submit').css('display', 'block');  
    }
    
            
    $('.adding_attr ').on('click','#add_feature', function(){
        $('.div_feature_group').css('display', 'block');
        $('.div_feature').css('display', 'block');
        $('#data_submit').css('display','block');
        $.ajax({
            type:"POST",
            url:'/get_groups',
            dataType:'json',
            success:function(data){
                   console.log(data);
                   var dis=$('<option  value="0" >Choose category</option>');
                     $('#feature_groups').append(dis);                  
                    $.each(data,function(key, value){                       
                    var option=$('<option></option>').attr('value', value.id).text(value.name);
                    $('#feature_groups').append(option); 
            })
        }
        })
    })
    
        $('.adding_attr ').on('click','#add_link', function(){        
        $('.div_link').css('display', 'block');
        $('#data_submit').css('display','block');
        $.ajax({
            type:"POST",
            url:'/get_areas',
            dataType:'json',
            success:function(data){
                   console.log(data);
                   var dis=$('<option  value="0" >Choose category</option>');
                     $('#links').append(dis);                  
                    $.each(data,function(key, value){                       
                    var option=$('<option></option>').attr('value', value.id).text(value.name);
                    $('#links').append(option); 
            })
        }
        })
    })
    
                
    $('.adding_attr ').on('click','#add_media', function(){
        $('.div_media').css('display', 'block');
        $('#data_submit').css('display','block');
        
    })
    
    $('.adding_attr ').on('click','#add_description', function(){
        $('.desc_div').css('display', 'block');
        var hidden_id=$('<input id="hidden_data" name="parent_id" type="hidden" value="'+this.value+'">');
        $("#hidden_data").remove();
        $(hidden_id).insertBefore('#add_feature');
        
    })
    
    $('.desc_save').click(function(){
        var textarea=$('<textarea type="hidden" name="description"></textarea>');
        var description=$("#summernote").code();  
        $(textarea).value(description);
        $(textarea).insertBefore('#add_feature');
        $( "#add_attr" ).submit();
        
    })
    
       
    
    $('.navigation').on("click",'.nav_button',function(){      
       var hidden_id=$('<input id="hidden_data" name="parent_id" type="hidden" value="'+this.value+'">');
      $("#hidden_data").remove();
        $(hidden_id).insertBefore('#add_feature');
       $('.adding_attr .intro').empty();
       $('.adding_attr  .intro').text('Insert data for '+this.innerText); 
       $('.adding_attr .showup').first().css('display','block');     
       var data={};
    //   data['type']=$(this).parent().attr('id');
   //    data['table']=this.name;
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
                $.each(data['children'],function(key, value){                
                    var button= $("<button></button>")
                        .attr("value",value['id'])
                        .attr('class','nav_button')                                                          
                        .attr('type','button')
                        .text(value['name'])                        
                    $(child_div).append(button);  
                 
                });
             //   if(data_type['table']){
                    $('#child_'+data_type['id']).remove(); 
                    $('#claster_'+data_type['id']).after(child_div);   
            //    }

                $.each(data['parent'],function(key, value){                  
                    $("div[id="+data_type['type']+'_'+data_type['id']+'_'+key+"]").remove();
                    if(key=="features" || key=="links"){
                            var topic=$('<h5></h5>').text(data_type['name']+' '+key);    
                            var data_div= $('<div></div>',{"class": "content clasters" });
                            $(data_div  ).attr('id',data_type['type']+'_'+data_type['id']+'_'+key);                     
                            var close_button=$("<button></button>").attr('id', 'close_'+key).attr('class', 'close_button').text('x');
                            var content_data_div=$('<div></div>',{"class": key });
                            var header_div=$('<div></div>').attr('class', 'topic');                            
                            $(header_div).append(close_button);
                            $(header_div).append(topic);
                            $(data_div).append(header_div);
                            var groups={};
                            $.each(value,function(feature_key, feature_val){
                                if(groups[feature_val['name']]=== undefined){
                                    groups[feature_val['name']]=[];
                                     groups[feature_val['name']].push(feature_val['data']);                                    
                                    
                                }
                                else{

                                  groups[feature_val['name']].push(feature_val['data']);
                                  }

                            })                                                    
                            $.each(groups, function(group_key, group_val){
                                   var subtopic=$('<b></b>').text(group_key);
                                   var sub_div=$('<div></div>').attr('class',group_key);
                                   $(sub_div).append(subtopic);
                                   $.each(group_val,function(k, v){                                        
                                        $(sub_div).append('<p>'+v+'</p>');
                                
                                   })
                                   $(content_data_div).append(sub_div);         
                                
                                $(data_div).append(content_data_div); 
                            }   )            
                           
                            $('#main_menu').after(data_div);   
 
                    }
                    else{

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

                    }    
             }) 
              
            },
                error: function(data, textStatus, errorThrown){
                     console.log(data);
                 }
        });
    } 

    
    
    
 })