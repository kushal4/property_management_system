<?php
$inputFileName = '..PhpSS/samples/Reading_workbook_data/sampleData/example1.xls';

/** Load $inputFileName to a Spreadsheet object **/
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
?>