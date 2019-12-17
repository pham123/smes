<?php 
function checkpattern($station,$products,$code){
    global $oDB;
    $labelpattern = $oDB->query('SELECT * FROM labelpattern WHERE TraceStationId = ? AND ProductsId =? ', $station,$products)->fetchArray();
    //var_dump($labelpattern);
    $pattern = $labelpattern['LabelPatternValue'];
    //exit();
    #Kiểm tra xem mã tem có đảm bảo không
    $check = 1;
    if (strlen($pattern)!=strlen($code)) {
        $_SESSION['message'] = "<h1 style='background-color:red;'>Độ dài tem ".$code." không hợp lệ .".$pattern."</h1>";
        header('Location: ?');
        exit();
    }
    for ($i=0; $i < strlen($code) ; $i++) { 
        if ( $pattern[$i]!='*') {
            if ( $pattern[$i]!=$code[$i]) {
                $_SESSION['message'] = "<h1 style='background-color:red;'>Cấu trúc tem ".$code." không hợp lệ</h1>";
            $check = 0;
            break;
            }
        }else{
            if ( !is_numeric($code[$i])) {
                $_SESSION['message'] = "<h1 style='background-color:red;'>Cấu trúc tem ".$code." không hợp lệ, phần chữ số có chứa kí tự</h1>";
                $check = 0;
                break;
                }
        }
    }
    if ($check == 0) {
        //$return['m'] = "<h1 style='background-color:red;'>Cấu trúc tem ".$code." không hợp lệ</h1>";
        
        header('Location: ?');
        exit();
    }
    return $labelpattern;
}

?>