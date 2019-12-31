<?php
    require("fpdf-library/fpdf.php");

    $con = mysqli_connect('127.0.0.1', 'root', 'rootPassword');
    if(!$con) {
        echo "Error: could not connect to MySQL database server!";
        exit;
    }

    if(!mysqli_select_db($con, 'pdf-example')) {
        echo "Error: database not selected!";
        exit;
    }

    $sql = "select * from gpa";
    $records = mysqli_query($con, $sql);

    $header = array("Semester No.", "Name", "Year", "GPA");

    // Create PDF
    $pdf = new FPDF();
    $pdf->SetFont('Arial','',14);
    $pdf->AddPage();

    // Colors, line width and bold font
    $pdf->SetFillColor(255, 0, 0);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(128, 0, 0);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('', 'B');

    // Header
    $w = array(40, 35, 40, 45);
    for($i=0; $i<count($header); $i++) {
        $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
    }
    $pdf->Ln();

    // Color and font restoration
    $pdf->SetFillColor(224, 235, 255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('');

    // Data
    $fill = false;
    while($row = mysqli_fetch_array($records)) {
        $pdf->Cell($w[0], 6, $row['gpa_index'], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[1], 6, $row['semester_name'], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[2], 6, $row['semester_year'], 'LR', 0, 'R', $fill);
        $pdf->Cell($w[3], 6, $row['semester_gpa'], 'LR', 0, 'R', $fill);

        $pdf->Ln();
        $fill = !$fill;
    }

    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
    $pdf->Output('F', 'gpa.pdf');
?>