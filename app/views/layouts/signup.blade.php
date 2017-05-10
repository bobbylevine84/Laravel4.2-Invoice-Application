<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Integrity Invoice - Signup</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}">	  
		<link rel="apple-touch-icon" href="{{ URL::asset('apple-touch-icon.png') }}">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
 
          <link rel="stylesheet" href="{{ URL::asset('assets/css/normalize.css') }}">
          <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
          <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
          <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic' rel='stylesheet' type='text/css'>
          <link rel="stylesheet" href="{{ URL::asset('assets/css/signup.css') }}">
          <link rel="stylesheet" href="{{ URL::asset('assets/css/password.css') }}">
          <script src="{{ URL::asset('assets/js/vendor/modernizr-2.6.2.min.js') }}"></script>
 

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
    
        
       
        <div class="page_wrap">
       	
        	<div id="page">
        		
        		<div class="container">
        			
        			@if (Session::get('flash_message'))
        				<div class="flash">{{ Session::get('flash_message') }}</div>
        			@endif
        			
        			@yield('content')
        			
        	 
        		</div><!-- container -->
       	
       		</div><!-- END page -->
       	
       </div><!-- END page_wrap -->
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{ URL::asset("assets/js/vendor/jquery-1.10.2.min.js") }}"><\/script>')</script>
  
        <script src="{{ URL::asset('assets/js/password.js') }}"></script>
        <script>
        	$(function(){
        		
        		$('.password_meter').hide();
        		
        		$('input[type=password]').on('keyup', function(){
			      $('.password_meter').fadeIn(1000);
			    });
			    
			     
        		
        		$referral_section = $('.referral_code_section');
        		
        		if($('.referral_code').val() == ""){
        			$referral_section.hide();
        		}
        		
        		$referral_text = $('.referral_text');
        		 
        		$reveal_referral = $('a.reveal_referral');
        		
        		$reveal_referral.click(function(){
	 	  			$referral_text.hide();
	 	  			$referral_section.fadeIn(400);
	 	  			return false;
	 			});	
        		
        	});
        </script>
   
    </body>
</html>
