<!doctype html>
<html lang="{{ app()->getLocale() }}">
	<head>

            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="{!! asset('css/app.css') !!}" rel="stylesheet" type="text/css" />
        <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">		<title>
			My Knowlege
		</title>
	</head>
	<body>
    <div class="main">
		<h1>
			Welcome to Knowlege Base
		</h1>
        </div>
        <div class="main_container col-md-12">
        <div class="nav">
        @foreach ($main_claster as $tab)
            <div class="shortcut nav-tabs">{{$tab}}</div>
        @endforeach


        </div>
            <div class="adding form-row col-xs-12">            
            {{ Form::open() }}
            {{csrf_field()}}
            <div class="form-group col-sm-2">
            {{ Form::label('type', 'Choose Type of category for adding', ['class' => 'awesome'])}}
            {{ Form::select('type', ['0'=>'Choose', 'Main_claster' => 'Main Claster', 'Claster' => 'Claster', 'Subject' => 'Subject' ], '0',['class' => 'form-control','required'])}}
            </div>
            <div id="flow_claster" class="showup form-group col-md-2">
                {{ Form::label('main_claster', 'Choose Main Claster', ['class' => 'awesome'])}}
                {{ Form::select('main_claster',$main_claster,0,['class' => 'form-control'])}}
            </div>
            <div id="flow_subject" class="showup form-group col-md-2">
                {{ Form::label('claster', 'ChooseClaster', ['class' => 'awesome'])}}
                {{ Form::select('claster',[], '0',['class' => 'form-control'])}}
            </div>
            <div id="flow_feature" class="showup form-group col-md-2">
                {{ Form::label('subject', 'Choose subject', ['class' => 'awesome'])}}
                {{ Form::select('Subject',[], '0',['class' => 'form-control'])}}
            </div>   
            <div id="flow_main_claster" class="showup form-group col-md-2">    
                {{ Form::label('name', 'Enter name', ['class' => 'awesome'])}}
                {{ Form::text('name','', array('required' => 'required', 'class'=>'form-control'))}}
             </div>
                
                {{ Form::submit('Add Data', array('id'=>'submit','class'=>'btn custom_button'))}}   		
                {{ Form::close() }}
         </div>
         <div class="adding_attr row ">
         <div class="showup">
             <div class="intro col-lg-2 col-md-2"></div>
                {{ Form::open(['files' => true, 'id'=>'add_attr']) }}
                {{csrf_field()}} 
                <div class="form_div col-md-3 col-lg-3">          
                {{Form::button( 'Feature', array('class' => 'btn', 'id'=>'add_feature'))}}            
                {{ Form::button( 'Media', array('class' => 'btn','id'=>'add_media')) }}
                {{ Form::button('Description', array('class' => 'btn', 'id'=>'add_description')) }} 
                {{ Form::button('Link', array('class' => 'btn', 'id'=>'add_link')) }} 
                </div> 
                 <div class="form_div col-md-7 col-lg-7">   
                <div  class="showup form-group col-md-3 col-sm-6 col-lg-3 div_link"> 
                {{Form::select ('links',[],'0',['class' => 'form-control', 'id'=>'links'])}}
                </div>               
                <div  class="showup form-group col-md-3 col-sm-6 col-lg-3  div_feature"> 
                {{Form::select ('feature_groups',[],'0',['class' => 'form-control', 'id'=>'feature_groups'])}}
                </div>                
                <div  class="showup form-group col-md-3 col-sm-6 col-lg-3 div_feature_group">
                {{ Form::text('new_feature_group','', array( 'class'=>'form-control','placeholder' =>'add new feature group' ))}}
                </div>
                <div  class="showup form-group col-md-3 col-sm-6 col-lg-3  div_media"> 
                {{ Form::file('media', ['class' => 'form-control'])}}
                </div>
                <div  class="showup form-group col-md-3 col-sm-6 col-lg-3 div_feature">                 
                {{ Form::text('feature','', array( 'class'=>'form-control'))}}                                
                </div>
              <!--   <div class="form-group">
 {{Form::label('body', 'Content')}}
 {{Form::textarea('body',null,array('class' => 'form-control', 'placeholder'=>'Content', 'id' => 'technig'))}}
 </div>-->
                <div  class="form-group col-md-3 col-sm-6 col-lg-2 "> 
                 {{ Form::submit('Add Data', array('id'=>'data_submit','class'=>'btn custom_button'))}} 
                 </div>
                 </div>
                {{ Form::close() }}
             </div>  
         </div>
         <div class="row">
            <div class="canvas col-md-12">
                <div id="main_menu" class="clasters col-md-2">            
                    <h5>Categories</h5>                              
                    <div class="navigation" id="claster">
                         @if($clasters)
                            @foreach ($clasters as $claster)
                                <button id="claster_{{$claster->id}}" name="subject" class="nav_button" type="button" value={{$claster->id}} >{{$claster->name}}</button>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="content clasters desc_div">
                    <textarea class="form-control" placeholder="Content" id="description" ></textarea>
                    <button class="btn custom_button desc_save">Save</button>
                </div>
            </div>
        </div> 
</div>

                 <script src="{{ asset('js/jquery-3.3.1.js') }}"></script>
                 <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

         <script src="{{ asset('js/main.js') }}"></script>
      <!--   <script src="{{ asset('js/app.js') }}"></script>-->
        
	</body>

</html>