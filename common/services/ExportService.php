<?php

namespace common\services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill; // Add this use statement

class ExportService{

    public static function exportExcel($sheetTitle = 'Export', $titles = [], $exportData = [], $otherData = []) {

        $objPHPExcel = new Spreadsheet();
        
        $sheetIndex = 0;
        $sheet = $objPHPExcel->createSheet($sheetIndex);
        $objPHPExcel->setActiveSheetIndex($sheetIndex);

        $sheet->setTitle($sheetTitle);

        $objPHPExcel->removeSheetByIndex(
            $objPHPExcel->getIndex(
                $objPHPExcel->getSheetByName('Worksheet')
            )
        );

        self::writeHeaderRow($objPHPExcel, $titles, $sheetTitle);
        self::writeData($objPHPExcel, $exportData);

        header('Content-Type: application/vnd.ms-excel');
        $filename = "ExcelReport_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        $objWriter = new Xlsx($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    public static function writeHeaderRow($objPHPExcel, $titles){
        $sheet = $objPHPExcel->getActiveSheet();
        $column = 'A';
        $sheet->getRowDimension('1')->setRowHeight(20);
        foreach ($titles as $key){
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $sheet->setCellValue($column.'1', $key);
            self::cellColor($objPHPExcel, $column.'1', 'C2FFBC');
            $column++;
        }
    }

    public static function writeData($objPHPExcel, $data){
        $sheet = $objPHPExcel->getActiveSheet();
        $row = 2;
        foreach ($data as $item) {
            $column = 'A';
            foreach ($item as $key => $value) {
                $sheet->setCellValue($column . $row, $value);
                $column++;
            }
            $row++ ;
        }
    }

    public static function cellColor(&$objPHPExcel, $cells, $color){
        $fill = $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill();
        $fill->setFillType(Fill::FILL_SOLID);
        $fill->getStartColor()->setARGB($color);
    }
}
?>