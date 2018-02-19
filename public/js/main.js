 jQuery(document).ready(function() {
        $('#description').summernote({
            //  height:100,
          
              minHeight: 300,             // set minimum height of editor
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
           $(".canvas").on("click",'img', function() {
//    console.log($(this));
    $('#imagepreview').attr('src', $(this).attr('src')); // here asign the image to the modal when the user click the enlarge link
    $('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
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
              //      console.log(data);
                    $('#'+type_for_adding).empty();
                    $.each(data,function(key, value){
                        
                       var option=$('<option></option>').attr('value', key).text(value);
                       $('#claster').append(option); 
                    })
                }
                })
            }
            else{
            //    console.log(id);
            }

    }  

    $('#type').change(function() {
        $('.showup').css('display','none')
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
//        console.log(el_for_change);
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
    
     function add_opt(data, name){
         var dis=$('<option  value="0" >Choose category</option>');
                     $(name).append(dis);                  
                    $.each(data,function(key, value){                       
                    var option=$('<option></option>').attr('value', value.id).text(value.name);
                    $(name).append(option); 
     }  )
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
                   add_opt(data, '#feature_groups');
                 
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
                  add_opt(data, '#link');
                  
           
        }
        })
    })
    
                
    $('.adding_attr ').on('click','#add_media', function(){
        $('.div_media').css('display', 'block');
        $('#data_submit').css('display','block');
        
    })
    
    $('.adding_attr ').on('click','#add_description', function(){
        $('.desc_div').css('display', 'block');

        
    })
    
    $('.desc_save').click(function(){
        var textarea=$('<textarea type="hidden" name="description"></textarea>').html($('#description').summernote('code'));
        $(textarea).insertBefore('#add_feature');
        $( "#add_attr" ).submit();
        
    })
    
    
    function create_topic(data_type, key, name){
     //  console.log(data_type);
                            var topic=$('<h5></h5>').text(name+' '+key);
                            var data_div= $('<div></div>').attr('id',data_type['parent']+'_'+data_type['id']+'_'+key);  
                            var close_button=$("<button></button>").attr('id', 'close_'+data_type['parent']+'_'+data_type['id']+'_'+key).attr('class', 'close_button').text('x'); 
                            if('0' in data_type){
                                
                                data_div= $('<div></div>').attr('id',data_type[0]['parent']+'_'+data_type[0]['id']+'_'+key); 
                                close_button=$("<button></button>").attr('id', 'close_'+data_type[0]['parent']+'_'+data_type[0]['id']+'_'+key).attr('class', 'close_button').text('x');   
                            } 
                            if(key!==''){
                                if(key=='media' || key=='description' || key=='' ){
                                    $(data_div).addClass('col-md-3 content clasters');
                                }
                                else{
                                    $(data_div).addClass('col-md-2 content clasters');
                                }  
                            }
                            else{
                                $(data_div).addClass('col-md-5 content clasters');
                            }

                          //  $(data_div  ).attr('id',data_type['type']+'_'+data_type['id']+'_'+key);                     
                            
                            
                            var header_div=$('<div></div>').attr('class', 'topic');                            
                            $(header_div).append(close_button);
                            $(header_div).append(topic);
                            $(data_div).append(header_div);
                            return (data_div);
                            

    }
    function create_card (key, value){
     //   console.log(value);
                                  var content_data_div=$('<div></div>',{"class": key });
                            var groups={};
                            $.each(value,function(feature_key, feature_val){
                                var id=feature_val['feature_id'];
                                if(id==undefined){
                                    id=feature_val['id'];
                                }
                                var data=feature_val['data'];
                                if(groups[feature_val['name']]=== undefined){
                                    groups[feature_val['name']]=[];
                                     groups[feature_val['name']].push({id:id,name:data});                                    
                                    
                                }
                                else{

                                  groups[feature_val['name']].push({id:id,name:data});
                                  }

                            })                                                    
                            $.each(groups, function(group_key, group_val){

                        //        console.log(group_val);
                                   var subtopic=$('<h5></h5>').text(group_key);
                                   var sub_div=$('<div></div>').attr('class',key);
                                   $(sub_div).append(subtopic);
                                   $.each(group_val,function(k, v){
                         //           console.log(v);
                                   var button=$('<button></button').attr('value',v.id).attr('class','nav_button').attr('type','button').text(v.name);
                                   $(sub_div).append(button);
                                
                             })
                                   $(content_data_div).append(sub_div);         
                               
  })
   return (content_data_div);
    }
    $('#data_sinc').click(function(){
        $.ajax({
            type:"POST",
            url:'/sinc_data',
            success:function(data){
                link_creator(data);
            //    console.log(data);
            }
        })
     //   link_creator({link: {25:{3:{id: 25, parent: 94, data: "<p><br></p><p>????? ??????? (????????).</p>", link: null, name: "??????? ??????? ???????? ????????????"}}},
     //                features: {4:{0:{area:"??????",data:"<p>????? ????????: ",group:"????",id:4,link:null,name:"??????? ??????? ??????? ?????",parent:56}}}});
    })
    
    function create_pos_card(type,data){
       
         var content_data_div=$('<div></div>',{"class": 'pos' });
         $.each(data, function(key, val){
             var content=$('<div></div>', {"class": "bottom_border"});
             var subtopic=$('<h5></h5>').text(val['name']);
             var description=$('<div></div>',{"class": 'content' }).append(val['data']);
             var create_link=$('<button></button>').attr('type','button').attr('class','add_btn').attr('value',type).text('Create '+type).attr('data-first',val['parent']).attr('data-second',val['id']);
             var ignore=$('<button></button>').attr('class','add_btn').attr('value',type+'_ignore').attr('type','button').text('Ignore').attr('data-first',val['parent']).attr('data-second',val['id']);
             $(content).append(subtopic);
             $(content).append(description);
             $(content).append(create_link);
             $(content).append(ignore);
             $(content_data_div).append(content);
         })

         return content_data_div;
    }
    function getFirstKey(value){
        
        for (i = 0; i < 100; i++) {            
            if(i in value){
                return(i);                
            }

        }
    }
    function link_creator(data){
     console.log(data);
        $.each(data['link'], function (key,value){             
            var first=getFirstKey(value);           
            var data_div=create_topic(value, '', 'Found link matches '+ value[first]['area']);
            var content_data_div=create_pos_card ('link', value);
                          $(data_div).append(content_data_div);                          
                          $('.canvas').append(data_div)
                         
        })
        $.each(data['features'],function(key,value){
            var first=getFirstKey(value);           
            var data_div=create_topic(value, '', 'Found feachers matches '+ value[first]['group']+': '+ value[first]['area']);
            var content_data_div=create_pos_card ('feature parent', value);
                          $(data_div).append(content_data_div);                          
                          $('.canvas').append(data_div)
        })
    }
    
    
    $('.canvas').on('click','.add_btn',function(){ 
        var button=this;
          var name=this.value;
          var data={ "_token": "3MH3ZTh9BzYikB1pHtNBQvOtjAZIPXQvJ5v5iSQM"};
            data[name]=this.dataset.first;
            data['parent']=this.dataset.second;             
            console.log(data);
            $.ajax({
                type: "POST",
                url: '/',
                dataType: 'json',
                data: data,            
                success: function( data ) {
                    console.log('yes');
                    console.log(data);
                },
                 error: function(data, textStatus, errorThrown){
                     console.log(errorThrown);
                     console.log(textStatus);             
                                         
                     $(button).parent().css( "display", "none" );
                 }
                
                })
    })
    function get_features_rel(button){
        var id={id:button.value};
       $.ajax({
            type: "POST",
            url: '/features_rel',
            dataType: 'json',
            data: id,            
            success: function( data ) {
            //   console.log(data);
            data.name=button.innerText;
               var data_div=create_topic(data, 'related');         
                 var content_data_div=create_card('related',data);      
              $(data_div).append(content_data_div); 
               $('#main_menu').after(data_div); 
            }
        })        
    }
       
    
    $('.main_container').on("click",'.nav_button',function(){ 
      //  console.log($(this).parent().attr('class'));
        if($(this).parent().attr('class')=='features'){
            get_features_rel(this);     
        }
        else{
            var hidden_id=$('<input id="hidden_data" name="parent" type="hidden" value="'+this.value+'">');
            $("#hidden_data").remove();
            $(hidden_id).insertBefore('#add_feature');
            $('.adding_attr .intro').empty();
            $('.adding_attr  .intro').text('Insert data for '+this.innerText); 
            $('.adding_attr .showup').first().css('display','block');     
            var data={};       
            data['id']=this.value;
            data['name']=this.innerText;       
            ajax_add_data(data);   
        }
        



    })   
        $('.main_container').on('click','.close_button',function(){
    //    console.log(this.id);
        $('#'+this.id).parent().parent().remove();
    }) 
            $('.main_container').on('click','.close_sub',function(){
    //    console.log(this.id);
        $('#'+this.id).parent().remove();
    }) 

    function ajax_add_data(data_type){ 
    //    console.log(data_type);
          $.ajax({
            type: "POST",
            url: '/ajax',
            dataType: 'json',
            data: data_type,            
            success: function( data ) {
//                console.log(data);
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
                    $('#child_'+data_type['id']).remove(); 
                    $('#claster_'+data_type['id']).after(child_div);   
     
                
                $.each(data['parent'],function(key, value){               
                    if(key=="features" || key=="links"){
                        $("div[id="+value[0]['parent']+'_'+value[0]['id']+'_'+key+"]").remove();
                          var data_div=create_topic(value, key, data['object'][0]['name']);
                          var content_data_div=create_card(key,value);
                          $(data_div).append(content_data_div);                          
                          $('.canvas').append(data_div); 
 
                    }
                    else{
                        $.each(value,function(child_key, child_value){
                           $("div[id="+child_value['parent']+'_'+child_value['id']+'_'+key+"]").remove();
                            var data_div=create_topic(child_value, key,data['object'][0]['name']);
                            var content_data_div=$('<div></div>',{"class": key });
                            $(content_data_div).append(child_value['data']) ;                                         
                            $(data_div).append(content_data_div); 
                            $('.canvas').append(data_div);   
                        })

                    }    
             }) 
              
            },
                error: function(data, textStatus, errorThrown){
//                     console.log(data);
                 }
        });
    } 

    

    
 })