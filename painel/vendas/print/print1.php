<?php

$pdfpath = 'doc.pdf';
$result = 'ok';

system('PDFToPrinter.exe ' . $pdfpath, $result);