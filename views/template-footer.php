  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>

  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script> 
  <!-- Latest compiled and minified JavaScript -->
  <script src="../vendor/select/dist/js/bootstrap-select.min.js"></script>
  <!-- (Optional) Latest compiled and minified JavaScript translation files -->
  <!-- <script src="../vendor/select/dist/js/i18n/defaults-*.min.js"></script> -->

  <script src="../vendor/country-picker-flags/js/countrySelect.js"></script>
  <script src="../vendor/datatables/dataTables.buttons.min.js"></script>
  <script src="../vendor/datatables/jszip.min.js"></script>
  <script src="../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../vendor/datatables/dataTables.fixedColumns.min.js"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- Page level custom scripts -->
  <script src="../js/demo/datatables-demo.js"></script>
  <script>
     $(document).on('test', function(e,code){
      $.ajax({
        url: 'ajaxupdatelang.php?code='+code,
        type: 'get',
        success: function(){
          location.reload(true);
        }
      })
    });

    $("#country_selector").countrySelect({
      onlyCountries: ['en','vi','kr','cn'],
      preferredCountries: []
    });
    $('.country-list').css('overflow','hidden');
    $("#country_selector").countrySelect("selectCountry",<?php echo json_encode($oDB->lang); ?>);

  </script>
  