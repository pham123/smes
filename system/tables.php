 <?php
 $headerar = $oDB->getcol($target);
 if($target=='Users'){
  $headerar = array_diff($headerar, array("EmployeesId"));
 }
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
              if (strpos( $value, 'CreateDate' ) !== false||strpos( $value, 'UpdateDate' ) !== false){

              }else{
                echo "<th>".$oDB->lang($value)."</th>";
              }
                
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
              if (strpos( $value2, 'CreateDate' ) !== false||strpos( $value2, 'UpdateDate' ) !== false) {
                //$date = new DateTime($ketqua[$i][$value2]);
                // $newDate = DateTime::createFromFormat("l dS F Y", $ketqua[$i][$value2]);
                // $newDate = $newDate->format('d/m/Y'); // for example
                //$date = new $ketqua[$i][$value2];
                // echo "<td>".($ketqua[$i][$value2]->format('d-M-y'))."</td>";
                // echo "<td>".$ketqua[$i][$value2]."</td>";
                // echo "<td></td>";
              }else{
                if($value2 == 'UsersName' && $target == 'Users'){
                  echo "<td><a href='manage_user_module.php?uid=".$ketqua[$i]['UsersId']."'>".$ketqua[$i][$value2]."</a></td>";
                }elseif($value2 == 'SectionName' && $target == 'Section'){
                  echo "<td><a href='assign_section.php?sid=".$ketqua[$i]['SectionId']."'>".$ketqua[$i][$value2]."</a></td>";
                }
                else{
                  echo "<td>".$ketqua[$i][$value2]."</td>";
                }
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