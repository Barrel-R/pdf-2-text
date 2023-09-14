<?php

require "vendor/autoload.php";

use Smalot\PdfParser\Parser;

$defaultPrefix = "parsed_pdf";

// Get all files in the script's directory that start with the default prefix.
$files = array_filter(scandir('./'), function ($file) {
    return str_starts_with($file, $GLOBALS['defaultPrefix']);
});

$defaultFileCount = count($files);
$defaultFileName = $defaultPrefix . '_' . ($defaultFileCount + 1);

$parser = new Parser();

$pdfPath = trim(readline("Inform the PDF path: "));

if (!file_exists($pdfPath)) {
    die("Couldn't file file at path '$pdfPath'.\n");
}

try {
    $pdf = $parser->parseFile($pdfPath);
} catch (Exception $e) {
    die("The file is not a valid PDF.\n");
}

$text = "";

foreach ($pdf->getPages() as $page) {
    $text .= $page->getText();
}

$outputPath = trim(readLine("Inform the path you wish to save the file (default: $defaultFileName): "));

if (empty($outputPath)) {
    $outputPath = $defaultFileName;
}

if (is_dir($outputPath)) {
    $finalDir = $outputPath . '/' . $defaultFileName;
} else {
    $finalDir = $outputPath;
}

file_put_contents($finalDir, $text);
die("PDF Text saved to $finalDir.\n");
