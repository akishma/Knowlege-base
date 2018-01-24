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
<div>{{ dd(get_defined_vars()['__data']) }}  </div>
</body>

</html>