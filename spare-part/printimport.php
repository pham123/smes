<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once('../config.php');
require_once('../function/db_lib.php');
require('../function/MysqliDb.php');
$oDB = new db();
$newDB = new MysqliDb(_DB_HOST_, _DB_USER_, _DB_PASS_,_DB_name_);
if (isset($_GET['id'])) {
  $import_id = $_GET['id'];
} else {
  header("Location:index.php");
}
$newDB->where('ImportsId', $import_id);
$newDB->join("SupplyChainObject s", "s.SupplyChainObjectId=i.SuppliersId", "LEFT");
$import = $newDB->getOne('Imports i');

$newDB->join("Inputs inp", "inp.ImportsId=i.ImportsId", "LEFT");
$newDB->join("Products p", "p.ProductsId=inp.ProductsId", "LEFT");
$newDB->join("SupplyChainObject s", "s.SupplyChainObjectId=i.SuppliersId", "LEFT");
$newDB->where('i.ImportsId', $import_id);
$products = $newDB->get('Imports i',null,'p.ProductsNumber,p.ProductsName,p.ProductsDescription,p.ProductsUnit,inp.ProductsQty,inp.ProductsUnitPrice');

// echo '<pre>';
// print_r($products);
// echo '</pre>';

// return;
?>
<!DOCTYPE html>
<html>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Smart information system">
    <meta name="author" content="Phạm Xuân Đồng">
    <link rel="shortcut icon" href="image/fifo-ico.png">
    <title>Print Export</title>

  <head>
      <style>
        html, body {
            width: 210mm;
            margin: 0 auto;
            border: 1px solid lightgrey;
        }
        #counter{
           /* counter-reset: headings;
           counter-reset: pages; */
        }
        /* table {
            table-layout: fixed;
        } */
        table td,th{
          border:1px solid black;
        }
      footer {
        font-size: 9px;
        color: #f00;
        text-align: center;
        
        }
      td,th{
        text-align:center; vertical-align:middle;
      }
      td{
        word-wrap: break-word;
      }
      td.last{
        white-space: nowrap;
      }

        @page {
        size: A4;
        /* counter-increment: page; */
        margin: 11mm 10mm 10mm 10mm;
        /* margin-bottom: 40px; */
        /* counter-increment: page; */
        @bottom-right {
          content: counter(page) " of " counter(pages);
        }
        @page :left: header {
        content: "Page " decimal(pageno), , first(chapter);
        font-variant: small-caps
        }
        @page :right :header {
            content: last(section), , "Page " decimal(pageno);
            font-variant: small-caps
        }
        }
        /* @page:first { counter-reset: page 9 } */
        @media print {
        footer {
            position: fixed;
            bottom: 0;
            counter-increment: pages;
        }
        .pagenum:after {
          counter-increment: pages;
        }
        .pagenum:before { 
          counter-increment: headings;
          /* content: "Page " counter(headings) " of " counter(pages);  */
        }
        .content-block, p {
            page-break-inside: avoid;
        }
        html, body {
            width: 210mm;
            height: 297mm;
            border: none;
        }
        #noprint{
          display:none;
        }
        }
      </style>
  </head>
  <body onload="window.print()">
    <div style='height:125px;'>
      <div style="width:30%; text-align:center; float:left;" id='counter'>
          <p style="text-align:center;">
          <img src="../img/hallalogo.png" alt="" style="width:120px">
          </p>
          <p>HALLA ELECTRONICS VINA</p>
      </div>
      <div style="width:70%; text-align:center;float:left;">
          <div id="approvedstamp" style="text-align:left;">
              <!-- <img src="image/approved.png" alt="" style="height:50px;margin-left:-20px;"></div> -->
              <div id="approvedstamp" style="text-align:right;margin-top:-40px;">
                <!-- <img src="image/hq.png" alt="" style="height:120px;"> -->
                </div>        
          </div>
      </div>    
    </div>
    <div style='width:100%; padding-bottom:20px;padding-left:10px;padding-right:10px;box-sizing: border-box;'>
        <h2 style='text-align: left;'>IMPORT SPARE PART</h2>
        <ul style="text-align: left;list-style:none;width: 300px;margin: 0 auto;margin-bottom: 20px;padding:0px;">
            <li><strong>PO:</strong> <?php echo $import['ImportsPO'] ?></li>
            <li><strong>Date:</strong> <?php echo date('d-m-Y',strtotime($import['ImportsDate'])) ?></li>
            <li><strong>Supplier:</strong> <?php echo $import['SupplyChainObjectName'] ?></li>
            <li><strong>Note:</strong> <?php echo $import['ImportsNote'] ?></li>
        </ul>
        <p style="margin-bottom: 8px;font-weight: bold;">Products detail</p>
        <table border="1" cellspacing="0" style="border-collapse:collapse; border:0.5pt solid windowtext; width:100%;box-sizing: border-box;">
	<tbody>
		<tr>
			<td style="background-color:#d0cece; height:30pt; text-align:center; vertical-align:middle; white-space:nowrap; width:31pt"><span style="font-size:11pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><strong>Index</strong></span></span></span></td>
			<td style="background-color:#d0cece; height:30pt; text-align:center; vertical-align:middle; white-space:normal; width:52pt"><span style="font-size:11pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><strong>Part No</strong></span></span></span></td>
			<td style="background-color:#d0cece; height:30pt; text-align:center; vertical-align:middle; white-space:normal; width:52pt"><span style="font-size:11pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><strong>Part Name</strong></span></span></span></td>
			<td style="background-color:#d0cece; height:30pt; text-align:center; vertical-align:middle; white-space:normal; width:100pt"><span style="font-size:11pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><strong>Spec</strong></span></span></span></td>
            <td style="background-color:#d0cece; height:30pt; text-align:center; vertical-align:middle; white-space:normal; width:45pt"><span style="font-size:11pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><strong>Unit</strong></span></span></span></td>
            <td style="background-color:#d0cece; height:30pt; text-align:center; vertical-align:middle; white-space:normal; width:48pt"><span style="font-size:11pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><strong>Qty</strong></span></span></span></td>
			<td style="background-color:#d0cece; height:30pt; text-align:center; vertical-align:middle; white-space:normal; width:48pt"><span style="font-size:11pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><strong>Unit Price</strong></span></span></span></td>
        </tr>
        <?php
        foreach($products as $key=>$product)
        {
        ?>
		<tr>
			<td style="height:14.4pt; text-align:center; vertical-align:middle; white-space:nowrap"><span style="font-size:11pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><strong><?php echo $key+1 ?></strong></span></span></span></td>
			<td style="height:14.4pt; text-align:center; vertical-align:middle; white-space:nowrap"><span style="font-size:12pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><?php echo $product['ProductsNumber'] ?></span></span></span></td>
			<td style="height:14.4pt; text-align:center; vertical-align:bottom; white-space:normal;"><span style="font-size:12pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><?php echo $product['ProductsName'] ?></span></span></span></td>
            <td style="height:14.4pt; text-align:center; vertical-align:middle; white-space:nowrap"><span style="font-size:12pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><?php echo $product['ProductsDescription'] ?></span></span></span></td>
            <td style="height:14.4pt; text-align:center; vertical-align:middle; white-space:nowrap"><span style="font-size:12pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><?php echo $product['ProductsUnit'] ?></span></span></span></td>
			<td style="height:14.4pt; text-align:center; vertical-align:middle; white-space:nowrap"><span style="font-size:12pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><?php echo $product['ProductsQty'] ?></span></span></span></td>
			<td style="height:14.4pt; text-align:center; vertical-align:middle; white-space:nowrap"><span style="font-size:12pt"><span style="color:black"><span style="font-family:Calibri,sans-serif"><?php echo number_format($product['ProductsUnitPrice'],0,'.',',') ?></span></span></span></td>
        </tr>
        <?php
        }
        ?>

</tbody>
</table>
<p style='text-align: right;margin-right: 10px;margin-top: 50px;'>Hải Phòng, ngày <?php echo date('d') ?> tháng <?php echo date('m') ?> năm <?php echo date('Y') ?></p>
<p style="height: 100px;">&nbsp;</p>
</div>
    

  </body>
</html>