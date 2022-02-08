<?php

namespace Nubesys\File\Pdf;

use Nubesys\File\File;
use Dompdf\Dompdf as DomPdfOriginal;

class DomPdf extends File {

    private $dompdf;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->dompdf       = null;
    }

    public function createPdfFromHtml($p_html){

        $this->dompdf       = new DomPdfOriginal();
        $this->dompdf->loadHtml($p_html);
        $this->dompdf->setPaper('A4', 'landscape');

        return $this->dompdf;
    }

    public function savePdfToFileSystem($p_name, $p_relativePath){

        $basePath = $this->getFileAbsolutePath($p_relativePath);

        $savePath = $basePath . '/' . $p_name . '.pdf';
        
        $this->dompdf->render();
        $output = $this->dompdf->output();

        file_put_contents($savePath, $output);
    }
}