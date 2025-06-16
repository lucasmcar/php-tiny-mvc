<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Compiled and minified CSS -->
    @css(https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css)
    @css(https://fonts.googleapis.com/icon?family=Material+Icons)
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"/>
    
    {{! $styles }}
    
    <title>{{ $titulo }}</title>
</head>
<body>   
    <main>
        {{ $content }}
    </main>
    <footer>
        
    </footer>
    @js( '/assets/js/debugger-screen.js' )

    {{! $scripts}}
</body>
</html>