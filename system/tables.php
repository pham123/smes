 <?php
 $sql = "Select *
 from ".$target." 
 WHERE ".$target."Id = 1
 ";
 $ketqua = $oDB-> fetchOne($sql);
 if ($ketqua==Null) {
   exit();
 }
 $headerar = (array_keys($ketqua));
 $text1 = '';
 $text2 = '';
 foreach ($headerar as $key => $value) {
     if (strpos( $value, $target ) !== false) {
         $text2=$text2.'
         '.$target.'.'.$value.',';
       }else{
         $SelectTable = str_replace('Id', '', $value);
         $text1= $text1.'
         Inner join '.$SelectTable.' on '.$target.'.'.$SelectTable.'Id = '.$SelectTable.'.'.$SelectTable.'Id' ;
         $text2=$text2.'
         '.$SelectTable.'.'.$SelectTable.'Name,';
         
       }
 }
 $text2 =rtrim($text2, ',');
 $sql = "Select 
 ".$text2."
 from ".$target." 
 ".$text1."
 ";

 $ketqua = $oDB-> fetchAll($sql);
 $headerar = (array_keys($ketqua[0]));

 //var_dump($ketqua);
 
 ?>
 
 <!-- DataTales Example -->
  <div class="card shadow mb-4">
            <h3><a href="<?php echo '?create_'.$target?>"><?php echo $oDB->lang("CreateNew") ?></a></h3>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
<?php
                    foreach ($headerar as $key => $value) {
                        echo "<th>".$oDB->lang($value)."</th>";
                    }
                    echo "<th>".$oDB->lang("Edit")."</th>";
?>
                    </tr>
                  </thead>
                  <tbody>
<?php
                  for ($i=0; $i < count($ketqua); $i++) { 
                      echo "<tr>";
                    foreach ($headerar as $key2 => $value2) {
                      if (strpos( $value2, 'Date' ) !== false) {
                        //$date = new DateTime($ketqua[$i][$value2]);
                        // $newDate = DateTime::createFromFormat("l dS F Y", $ketqua[$i][$value2]);
                        // $newDate = $newDate->format('d/m/Y'); // for example
                        //$date = new $ketqua[$i][$value2];
                        // echo "<td>".($ketqua[$i][$value2]->format('d-M-y'))."</td>";
                        // echo "<td>".$ketqua[$i][$value2]."</td>";
                        echo "<td></td>";
                      }else{
                        echo "<td>".$ketqua[$i][$value2]."</td>";
                      }
                    }
                      echo "<td><a href='?update_".$target."_".$ketqua[$i][$target.'Id']."'><i class='fas fa-fw fa-edit'></i></a></td>";
                      echo "</tr>";
                  }
?>                   
                  </tbody>
                </table>
              </div>
            </div>
          </div>