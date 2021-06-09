<?php

require('../pdf/fpdf.php');

class PDF extends FPDF
{
  // Page header
  function Header()
  {
    // Arial bold 15
    $this->SetFont('Arial', 'B', 20);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30, 10, 'Recibo de nomina', 0, 0, 'C');
    // Line break
    $this->Ln(15);
  }

  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial', 'I', 10);
    // Page number
    $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
  }
}
