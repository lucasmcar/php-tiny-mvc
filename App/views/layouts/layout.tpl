<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Compiled and minified CSS -->
    @css(https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css)
    @css(https://fonts.googleapis.com/icon?family=Material+Icons)
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"/>
    
    {{ $styles }}
    
    <style>
        footer{
            box-sizing: border-box;
            position: fixed;
            bottom: 0;
            border: 1px solid red;
            width: 100%;
        }
    </style> 
    <title>{{ $title }}</title>
</head>
<body>   
    <main>
        {{ $content }}
    </main>
    <footer>
        <p>&copy; {% year %} Tiny MVC</p>
    </footer>
    @js( '/assets/js/debugger-screen.js' )

    {{ $scripts}}
</body>
</html>