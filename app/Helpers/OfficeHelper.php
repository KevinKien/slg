<?php

namespace App\Helpers;

use PHPExcel, PHPExcel_IOFactory;

class OfficeHelper
{
    private static function generateExcelColumn()
    {
        $c = 0;
        $str_arr = [];
        for ($i = 0; $i < 26; $i++) {
            $str_arr[$c] = chr(65 + $i);
            $c++;
        }
        for ($i = 0; $i < 26; $i++) {
            for ($j = 0; $j < 26; $j++) {
                $str_arr[$c] = chr(65 + $i) . chr(65 + $j);
                $c++;
            }
        }
        return $str_arr;
    }

    private static function getFileExtensionWriter($file_name)
    {
        switch (strtolower(pathinfo($file_name, PATHINFO_EXTENSION))) {
            case 'xlsx':            //  Excel (OfficeOpenXML) Spreadsheet
                $extension_type = ['mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'writer' => 'Excel2007'];
                break;
            case 'xls':             //  Excel (BIFF) Spreadsheet
                $extension_type = ['mime' => 'application/vnd.ms-excel', 'writer' => 'Excel5'];
                break;
            case 'csv':
                $extension_type = ['mime' => 'text/csv', 'writer' => 'CSV'];
                break;
            case 'pdf':
                $extension_type = ['mime' => 'application/pdf', 'writer' => 'PDF'];
                break;
            default:
                $extension_type = ['mime' => 'application/vnd.ms-excel', 'writer' => 'Excel5'];
                break;
        }

        return $extension_type;
    }

    /**
     * @author VuongCH
     * @created Oct 29, 2012
     * @param array $reports
     * @param string $file_name
     * @param string $sheet_title
     * @param string $font_size
     * @param string $font_face | default: Calibri
     */
    public static function exportExcel($reports, $file_name = 'Report.xls', $sheet_title = 'Report', $font_size = '12', $font_face = null)
    {
        $char = self::generateExcelColumn();
        $file_info = self::getFileExtensionWriter($file_name);

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);

        if (!is_null($font_face)) {
            $objPHPExcel->getActiveSheet()->getStyle()->getFont()->setName($font_face);
        }

        $objPHPExcel->getActiveSheet()->getStyle()->getFont()->setSize($font_size);
        $objPHPExcel->getActiveSheet()->setTitle(substr($sheet_title, 0, 30));

        $c = 0;
        $r = 1;
        foreach ($reports as $title => $report) {
            $objPHPExcel->getActiveSheet()->setCellValue($char[$c] . $r++, $title);
            foreach ($report as $data) {
                $objPHPExcel->getActiveSheet()->setCellValue($char[$c] . $r++, $data);
            }
            $c++;
            $r = 1;
        }

        $styles = array('font' => array('bold' => true));

        for ($i = 0; $i < count($reports); $i++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($char[$i])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($char[$i] . $r)->applyFromArray($styles);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $file_info['writer']);
        $file_location = tempnam(sys_get_temp_dir(), $file_name);
        $objWriter->save($file_location);

        $content = file_get_contents($file_location);

        if (strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) === 'csv') {
            $content = "\xEF\xBB\xBF" . $content; // UTF-8 BOM
        }

        return response($content)
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Content-Encoding', 'UTF-8')
            ->header('Content-Type', $file_info['mime'] . ';charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename=' . basename($file_name))
            ->header('Content-Transfer-Encoding', 'binary')
            ->header('Content-Length', filesize($file_location));
    }
}