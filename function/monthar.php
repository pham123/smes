<?php
function monthar( $datestart,$Totalcol){
    for ($i=0; $i < $Totalcol  ; $i++) { 
        $k = $i;
        $monthar[$i]['start'] = date('Y-m-01', strtotime('+'.$k.' month', strtotime($datestart)));
        $monthar[$i]['end'] = date('Y-m-t', strtotime('+'.$k.' month', strtotime($datestart)));
    }
    return $monthar;
}

function monthback( $datestart,$Totalcol){
    for ($i=0; $i <$Totalcol; $i++) { 
        // $k = $Totalcol-$i;
        $monthar[$i]['start'] = date('Y-m-01', strtotime('-'.$i.' month', strtotime($datestart)));
        $monthar[$i]['end'] = date('Y-m-t', strtotime('-'.$i.' month', strtotime($datestart)));
    }
    return $monthar;
}
