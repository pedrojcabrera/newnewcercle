<?php
if (!function_exists('muestra')) {
    function muestra($var = null, $namevar = null, $exit = false)
    {
        echo "<pre>
                <div style='
                    margin: 1rem 2rem;
                    width: max-content;
                    border: thin solid grey;
                    padding: .5rem 1rem;
                    box-shadow: 2px 2px 3px black
                '>";
        if ($namevar != NULL) {
            echo "<h4><b>" . $namevar . " (".gettype($var).") :</b></h4>";
        }
        switch (gettype($var)) {
            case "boolean":
                echo $var . " = " . ($var == true ? "Cierto" : "Falso");
                break;
            case "integer":
                echo $var;
                break;
            case "double":
                echo $var;
                break;
            case "float":
                echo $var;
                break;
            case "string":
                echo $var;
                break;
            case "array":
                print_r($var);
                break;
            case "object":
                print_r($var);
                break;
            case "resource":
                echo $var;
                break;
            case "resource (closed)":
                echo $var;
                break;
            case "NULL":
                echo "NULL";
                break;
            case "unknown type":
                echo $var;
                break;
            }
        echo "</div></pre>";
        if($exit) exit('Salida programada');
    }
}

// if (!function_exists('d_pause')) {
//     function d_pause($var) {
//         d($var);
//         readline("Presiona Enter para continuar...");
//     }
// }

if (!function_exists('uti_fecha')) {
    function uti_fecha($fecha) {
        return Datetime::createFromFormat('Y-m-d',$fecha)->format('d/m/Y');
    }
}

if (!function_exists('uti_estado_evento')) {
    function uti_estado_evento($desde,$hasta) {

        $hoy = date('Y-m-d');

        if ($desde <= $hoy AND $hasta >= $hoy) {
            $estado = "EN CURSO";
        } elseif ($desde > $hoy) {
                $estado = "PROXIMAMENTE";
            } elseif ($hasta < $hoy) {
                    $estado = "FINALIZADO";
                } else {
                    $estado = "MUY MAL";
                }

        return $estado;
    }
}
    
if (!function_exists('uti_quita_')) {
    function uti_quita_($string) {
        return strtolower(str_replace(' ', '', $string));
    }
}