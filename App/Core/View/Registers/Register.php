<?php 

namespace App\Core\View\Registers;

/**
 * This class is
 */
class Register
{
    protected static $patterns = [
        '/\{\{\s*(.+?)\s*\}\}/',                         // Variables
        '/\{\%\s*if\s+(.+?)\s*\%\}/',                    // If statement
        '/\{\%\s*elseif\s+(.+?)\s*\%\}/' ,               //elseif
        '/\{\%\s*else\s*\%\}/',                          //else
        '/\{\%\s*endif;\s*\%\}/',                        // End if statement
        '/\{\%\s*foreach\s*(\$\w+)\s*\%\}/',             // Foreach simplificado: {% foreach $array %}
        '/\{\%\s*foreach\s*(.+?)\s*as\s*(.+?)\s*=>\s*(.+?)\s*\%\}/', // Foreach statement
        '/\{\%\s*endforeach;\s*\%\}/',                   // End foreach statement
        '/\{\%\s*while\s+(.+?)\s*\%\}/',                 // While statement
        '/\{\%\s*endwhile;\s*\%\}/',                     // End while statement
        '/\{\%\s*include\s+\'(.+?)\'\s*\%\}/',           // Include statement
        //'/\bnot\b/',                                     // Not operator
        //'/\band\b/',                                     // And operator
        //'/\bor\b/',                                      // Or operator
        '/\{\%\s*debugger\s*\(\s*(.+?)\s*\)\s*\%\}/',
        '/\@css\(\s*(.+?)\s*\)/',
        '/@js\(\s*["\'](.+?)["\']\s*\)/',                // Include JS
        '/@csrf/', 
        '/\{\%\s*year\s*\%\}/', //Current Year,
        //'/@scripts\(\s*(.+?)\s*\)/',
        //'/@styles\(\s*(.+?)\s*\)/',
        //'/\{\{\s*\$(styles|scripts)\s*\}\}/' 
        '/@for\(\s*(.+?),\s*(.+?),\s*(.+?)\s*\)/',                                      
        '/@endfor\(\)/',
        '/\{\s*var\s*(.+?)\s*\}/'                                      
    ];

    protected static $replacements = [
        '<?php echo $1; ?>',
        '<?php if ($1): ?>',
        '<?php elseif ($1): ?>',
        '<?php else: ?>',
        '<?php endif; ?>',
        '<?php foreach ($1 as $item): ?>', // Foreach simplificado
        '<?php foreach ($1 as $2 => $3): ?>',
        '<?php endforeach; ?>',
        '<?php while ($1): ?>',
        '<?php endwhile; ?>',
        '<?php include __DIR__ . "/../../views/$1.tpl"; ?>',
        //'!',
        //'&&',
        //'||',
        '<?php print_r($1); ?>',
        '<link rel="stylesheet" href="$1">',
        '<script src="$1"></script>',
        '<input type="hidden" name="_csrf_token" id="_csrf_token" value="<?php echo $this->vars["csrf_token"]; ?>">',
        '<?php echo date("Y"); ?>',
        /*'<?php $1 = is_array($1) ? $1 : []; foreach ($1 as $script) { echo "<script src=\'$script\'></script>\n"; } ?>',
        '<?php $1 = is_array($1) ? $1 : []; foreach ($1 as $style) { echo "<link rel=\'stylesheet\' href=\'$style\'>\n"; } ?>',*/
        '<?php for( $i=$1; $i<=$2; $3++ ): ?>',
        '<?php endfor; ?>',
        '<?php $1 ?>'
    ];

    public function __construct()
    {
        $this->replacements;
    }

    public static function getReplacements() : array
    {
        return self::$replacements;
    }

    public static function getPatterns() : array
    {
        return self::$patterns;
    }
}