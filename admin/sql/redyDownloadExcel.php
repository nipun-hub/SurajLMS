<?php

use function PHPSTORM_META\type;

try {
    include('conn.php');
} catch (EXception $e) {
    echo "Connection Error";
}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

try {

    $type = $_POST['type'];
    $data = empty($_POST['data']) ? null : $_POST['data'];
    $method = empty($_POST['method']) ? null : $_POST['method'];
    if ($type == 'OldPeaperData') {
        $sheet->setCellValue('B2', 'Summary For ' . $method . ' ranking peaper');
        $sheet->mergeCells('B2:K2');
        $sheet->getStyle('B2:K2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B3', 'Name');
        $sheet->mergeCells('B3:D3');

        $sheet->setCellValue('E3', 'Exam Id');
        $sheet->mergeCells('E3:F3');

        $sheet->setCellValue('G3', 'Institute');
        $sheet->mergeCells('G3:H3');

        $sheet->setCellValue('I3', 'Marks');
        $sheet->mergeCells('I3:J3');

        $sheet->setCellValue('K3', 'Pass');

        $sheet->getStyle('B2:K3')->getFont()->setBold(true);

        $sheet->getStyle('A3:K3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF808080');
        $sheet->getStyle('B3:K3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF606060');
        // $sheet->mergeCells('B2:E2');

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];


        $i = 4;
        $sql = "SELECT * FROM peaper,marksofpeaper WHERE peaper.PeaperId = '$data' and peaper.PeaperId = marksofpeaper.PeaperId ORDER BY marksofpeaper.Marks DESC";
        $stmt = $conn->prepare($sql);
        // isset($_POST['data']) ? $stmt->bind_param("ssss", $data, $data, $data, $peaperId) : null;
        $stmt->execute();
        $reusaltMain = $stmt->get_result();
        $stmt->close();
        while ($reusaltMain->num_rows > 0 && $rowMain = $reusaltMain->fetch_assoc()) {
            // $PeaperId = $rowMain['PeaperId'];
            $URGId = $rowMain['URGId'];
            $UserIdstu = $rowMain['UserId'];
            $UserMarks = $rowMain['Marks'];
            $PeaperName = $rowMain['peaperName'];
            $pass = (($rowMain['Marks'] >= 74) ? "A" : (($rowMain['Marks'] > 64) ? "B"  : (($rowMain['Marks'] > 54) ? "C" : (($rowMain['Marks'] > 44) ? "S" : "F"))));
            if ($UserIdstu != null) {
                $sql = "SELECT UserName,InstiId,InstiName FROM user WHERE UserId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $UserIdstu);
                $stmt->execute();
                $reusaltUserData = $stmt->get_result();
                $stmt->close();
                if ($reusaltUserData->num_rows > 0 && $rowUserData = $reusaltUserData->fetch_assoc()) {
                    $UserName =  $rowUserData['UserName'];
                    $InstiId =  $rowUserData['InstiId'];
                    $InstiName =  $rowUserData['InstiName'];
                }
            } else {
                $sql = "SELECT Name,InstiName,CousId FROM unreguser WHERE URGId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $URGId);
                $stmt->execute();
                $reusaltUserData = $stmt->get_result();
                $stmt->close();
                if ($reusaltUserData->num_rows > 0 && $rowUserData = $reusaltUserData->fetch_assoc()) {
                    $UserName =  $rowUserData['Name'];
                    $InstiId =  $rowUserData['CousId'];
                    $InstiName =  $rowUserData['InstiName'];
                }
            }

            if ($method != 'iland' && $method != $InstiName) {
                continue;
            }

            $sheet->setCellValue('B2', 'Summary For ' . $method . " ranking peaper ( {$PeaperName} )");

            $sheet->setCellValue('B' . $i, $UserName);
            $sheet->mergeCells("B{$i}:D{$i}");

            $sheet->setCellValue('E' . $i, $InstiId);
            $sheet->mergeCells("E{$i}:F{$i}");

            $sheet->setCellValue('G' . $i, $InstiName);
            $sheet->mergeCells("G{$i}:H{$i}");

            $sheet->setCellValue('I' . $i, $UserMarks);
            $sheet->mergeCells("I{$i}:J{$i}");

            $sheet->setCellValue('K' . $i, $pass);
            // $sheet->mergeCells("B{$i}:D{$i}");
            $i++;
        }
        $i = $i - 1;
        $sheet->getStyle("B2:K{$i}")->applyFromArray($border);
    }
    if (!($reusaltMain->num_rows > 0)) {
        $sheet->setCellValue('B4', "Reusalt Not Found");
        $sheet->mergeCells("B4:k4");
        $sheet->getStyle("B2:K4")->applyFromArray($border);
    }



    $writer = new Xlsx($spreadsheet);
    $writer->save('export.xlsx');
    $respons = "success";
} catch (\Throwable $th) {
    $respons = "error : " . $th;
}

echo $respons;
