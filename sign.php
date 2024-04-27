<?php

require_once 'vendor/autoload.php';

use phpseclib\Crypt\RSA;
use setasign\Fpdi\Tcpdf\Fpdi;

// Путь к вашему приватному ключу
$privateKeyPath = '/path/to/private/key.pem';
$publicKeyPath = '/path/to/public/key.pem';

// Загрузка ключей
$privateKey = file_get_contents($privateKeyPath);

// Создание объекта PDF
$pdf = new FPDI();

// Загрузка PDF файла
$pageCount = $pdf->setSourceFile("sample.pdf");
$tplIdx = $pdf->importPage(1);
$pdf->addPage();
$pdf->useTemplate($tplIdx, 10, 10, 200);

// Добавление текста (штампа) в PDF
$pdf->SetFont('Helvetica');
$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY(25, 25);
$pdf->Write(0, 'Document Signed Electronically.');

// Подписание PDF
$rsa = new RSA();
$rsa->loadKey($privateKey);
$signature = $rsa->sign($pdf->Output('S'));

// Сохранение подписи в файл (опционально)
file_put_contents('signature.txt', $signature);

// Сохранение и вывод PDF файла
$pdf->Output('signed.pdf', 'I');
