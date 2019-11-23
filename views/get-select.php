<?php
          $SelectSql = "Select ".$SelectTable."Id, ".$SelectTable."Name From ".$SelectTable;
          $SelectAr = $oDB-> fetchAll($SelectSql);

          // $selectvalue = explode('_',$value);
          echo   "<select name='".$curenttable."_".$SelectTable."Id' id='".$curenttable."' class='selectpicker show-tick' data-live-search='true' data-style='btn-info' data-width='100%'>";
          echo   "<option value=''></option>";
          foreach ($SelectAr as $SelectKey => $SelectValue) {
            if ($ketqua[$curenttable.$SelectTable.'Id']==$SelectValue[$SelectTable.'Id']) {
              echo   "<option value='".$SelectValue[$SelectTable.'Id']."' Selected>".$SelectValue[$SelectTable.'Name']."</option>";
            } else {
              echo   "<option value='".$SelectValue[$SelectTable.'Id']."'>".$SelectValue[$SelectTable.'Name']."</option>";
            }
          }
          echo   "</select>"; 
