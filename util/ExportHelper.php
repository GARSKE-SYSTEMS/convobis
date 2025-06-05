<?php

namespace Convobis\Util;

class ExportHelper
{
    /**
     * Export data array to CSV string
     * @param array $data Array of associative arrays
     * @return string CSV formatted string
     */
    public static function toCSV(array $data): string
    {
        if (empty($data)) {
            return '';
        }
        ob_start();
        $fp = fopen('php://output', 'w');
        fputcsv($fp, array_keys(reset($data)));
        foreach ($data as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
        return ob_get_clean();
    }

    /**
     * Export data array to PDF using TCPDF (requires tcpdf library)
     * @param array $data
     * @return string PDF binary content
     */
    public static function toPDF(array $data): string
    {
        // Placeholder: implement using TCPDF
        return '';
    }
}
