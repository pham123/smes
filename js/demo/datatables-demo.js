// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable( {
      dom: "<'row'<'col-md-10 pull-left'f><'col-md-2 pull-right'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      buttons: [
          // 'copy', 'csv', 'excel', 'pdf', 'print'
          'excel','copy'
      ],
      language: {
          search: "",
          searchPlaceholder: "Search..."
      }
      //"paging": false
  } );
} );

$(document).ready(function() {
    $('#datatablenotpage').DataTable( {
        dom: "<'row'<'col-md-10 pull-left'f><'col-md-2 pull-right'B>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            'excel','copy'
        ],
        language: {
            search: "",
            searchPlaceholder: "Search..."
        },
        "paging": false
    } );
  } );

  $(document).ready(function() {
    $('#datatablenotdl').DataTable( {
        dom: "<'row'<'col-md-10 pull-left'f><'col-md-2 pull-right'B>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            // 'excel','copy'
        ],
        language: {
            search: "",
            searchPlaceholder: "Search..."
        },
        // "paging": false
    } );
  } );