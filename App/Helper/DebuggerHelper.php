<?php

namespace App\Helper;

class DebuggerHelper
{
    /**
     * @param array $vars Váriavel a ser analisada
     */
    public static function debugger(array $vars, int $xdebugVars = 0)
    {
        echo '<style>
        pre {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            overflow: auto;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .dd-keyword {
            color: #a71d5d;
            font-weight: bold;
        }
        .dd-value {
            color: #0086b3;
        }
        .dd-type {
            color: #333;
            font-weight: bold;
        }
      </style>';
    foreach ($vars as $var) {
        echo '<pre>';
        if (function_exists('xdebug_var_dump') && $xdebugVars !== 0) {
            ob_start();
            xdebug_var_dump($var);
            $output = ob_get_clean();
            echo self::dd_highlight($output);
        
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            echo self::dd_highlight($output);
        }
        echo '</pre>';
    }
    die(1); // Termina a execução do script

    
}

private static function dd_highlight($output) 
{
    $patterns = [
        '/(array\(\d+\))/' => '<span class="dd-type">$1</span>',
        '/(string\(\d+\))/' => '<span class="dd-type">$1</span>',
        '/(int\(\d+\))/' => '<span class="dd-type">$1</span>',
        '/(float\(\d+\.\d+\))/' => '<span class="dd-type">$1</span>',
        '/(bool\(true|false\))/' => '<span class="dd-type">$1</span>',
        '/(NULL)/' => '<span class="dd-type">$1</span>',
        '/(\[.+?\])/'=> '<span class="dd-keyword">$1</span>',
        '/=>\s(.+)/' => '=> <span class="dd-value">$1</span>'
    ];

    foreach ($patterns as $pattern => $replacement) {
        $output = preg_replace($pattern, $replacement, $output);
    }

    return $output;
}
}