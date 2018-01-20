<!doctype html>
<html lang="{{ app()->getLocale() }}">
	<head>

            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="{!! asset('css/app.css') !!}" rel="stylesheet" type="text/css" />
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
            <h5>Adding form</h5>
            {{ Form::open() }}
            {{csrf_field()}}
            <div class="form-group col-md-2">
            {{ Form::label('type', 'Choose Type', ['class' => 'awesome'])}}
            {{ Form::select('type', ['0'=>'Choose','test'=>'test', 'Main_claster' => 'Main Claster', 'Claster' => 'Claster', 'Subject' => 'Subject', 'Feature' => 'Feature', 'Description' => 'Description', 'Media' => 'Media' ], '0',['class' => 'form-control'])}}
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
                {{ Form::select('subject',[], '0',['class' => 'form-control'])}}
            </div>   
            <div id="flow_main_claster" class="showup form-group col-md-2">    
                {{ Form::label('name', 'Enter name', ['class' => 'awesome'])}}
                {{ Form::text('name','', array('required' => 'required', 'class'=>'form-control'))}}
             </div>
                
                {{ Form::submit('Add Data', array('id'=>'submit','class'=>'btn'))}}   		
                {{ Form::close() }}
         </div>
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

            </div>
        </div> 

                 <script src="{{ asset('js/jquery-3.3.1.js') }}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

         <script src="{{ asset('js/main.js') }}"></script>
      <!--   <script src="{{ asset('js/app.js') }}"></script>-->
         <div>{{ dd(get_defined_vars()['__data']) }}  </div>
	</body>

</html>