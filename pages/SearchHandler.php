<?php
// filepath: /home/patrick/Dokumente/Code Projects/PHP/convobis/pages/SearchHandler.php

use VeloFrame as WF;
require_once __DIR__ . '/../util/AuthHelper.php';
require_once __DIR__ . '/../service/SearchService.php';
require_once __DIR__ . '/../util/ExportHelper.php';

# @route search
class SearchHandler extends WF\DefaultPageController {
    public function handleGet(array $params): string {
        \Convobis\Util\AuthHelper::requireAuth();
        $term = WF\Input::sanitize('q', WF\Input::INPUT_TYPE_STRING, '');
        $service = new \Convobis\Service\SearchService();
        $results = $service->search($term);

        $tpl = new WF\Template('search_results');
        $tpl->includeTemplate('head', new WF\Template('std_head'));
        $tpl->includeTemplate('js_deps', new WF\Template('js_deps'));
        $tpl->setVariable('term', $term);
        $tpl->setVariable('results', $results);
        return $tpl->output();
    }

    public function handlePost(array $params): void {
        \Convobis\Util\AuthHelper::requireAuth();
        $type = $params['type'] ?? 'csv';
        $data = json_decode($params['data'], true);
        if ($type === 'csv') {
            $csv = \Convobis\Util\ExportHelper::toCSV($data);
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="export.csv"');
            echo $csv;
        } else {
            // PDF export
            $pdf = \Convobis\Util\ExportHelper::toPDF($data);
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="export.pdf"');
            echo $pdf;
        }
        exit;
    }
}