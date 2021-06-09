<?php

require('./layout.php');
include '../includes/connection.php';
include '../includes/helpers.php';

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 10);

$cheques = get_vistaCheques($db);

$firma = get_firmaDigital();

while ($cheque = mysqli_fetch_array($cheques)) {

  $pdf->AddPage('PORTRAIT', 'LETTER');

  /* --- Titulo --- */
  $pdf->SetFont('Arial', 'B', 18);
  $pdf->Cell(40, 10, 'Datos del empleado');

  $pdf->SetFont('Arial', '', 12);
  $pdf->Ln();
  // $pdf->SetXY(11, 27);

  /* --- Nombre --- */
  $pdf->Cell(20, 8, 'Nombre:');
  $pdf->Cell(20, 8, utf8_decode($cheque['Nombre']));
  $pdf->Ln();

  /* --- Puesto --- */
  $pdf->Cell(20, 8, 'Puesto:');
  $pdf->Cell(20, 8, $cheque['Puesto']);
  $pdf->Ln();

  /* --- RFC --- */
  $pdf->Cell(20, 8, 'RFC:');
  $pdf->Cell(20, 8, $cheque['RFC']);
  $pdf->Ln();

  /* --- CURP --- */
  $pdf->Cell(20, 8, 'CURP:');
  $pdf->Cell(20, 8, $cheque['CURP']);
  $pdf->Ln();

  /* --- No. de Nomina --- */
  $pdf->SetXY(107, 30);
  $pdf->Cell(40, 8, 'No. de Cheque:');
  $pdf->SetFont('Arial', 'B', 18);
  $pdf->Cell(20, 8, $cheque['idCheque']);

  $pdf->Ln();
  $pdf->Ln();

  // Separador
  $pdf->Line(10, 85, 200, 85);

  /* --- Titulo Percepciones --- */
  $pdf->SetXY(10, 95);
  $pdf->SetFont('Arial', 'B', 18);
  $pdf->Cell(40, 8, 'Percepciones');
  $pdf->Ln();

  /* --- Percepción Sueldo --- */
  $pdf->SetFont('Arial', '', 12);
  $pdf->Cell(40, 8, 'Sueldo normal:');

  /* --- Sueldo --- */
  $pdf->SetXY(50, 103);
  $pdf->Cell(40, 8, '$ ' . $cheque['sueldo_base']);

  // Separador
  $pdf->Line(10, 120, 200, 120);

  /* --- Titulo Retenciones --- */
  $pdf->SetXY(10, 130);
  $pdf->SetFont('Arial', 'B', 18);
  $pdf->Cell(40, 8, 'Retenciones');
  $pdf->Ln();

  /* --- Retencion ISR --- */
  $pdf->SetFont('Arial', '', 12);
  $pdf->Cell(40, 8, 'I.S.R:');

  /* --- Retencion ISR --- */
  $pdf->SetXY(55, 137);
  $pdf->Cell(40, 8, '$ ' . $cheque['descuento_isr']);

  /* --- Retencion Retiro --- */
  $pdf->Ln();
  $pdf->Cell(40, 8, 'Ahorro para el retiro:');

  /* --- Retencion Retiro --- */
  $pdf->SetXY(55, 145);
  $pdf->Cell(40, 8, '$' . $cheque['descuento_retiro']);

  /* --- Retencion Vivienda --- */
  $pdf->Ln();
  $pdf->Cell(40, 8, 'Ahorro vivienda:');

  /* --- Retencion Vivienda --- */
  $pdf->SetXY(55, 153);
  $pdf->Cell(40, 8, '$' . $cheque['descuento_vivienda']);

  /* --- Retencion Seguro Social --- */
  $pdf->Ln();
  $pdf->Cell(40, 8, 'Ahorro seguro social:');

  /* --- Retencion Seguro Social --- */
  $pdf->SetXY(55, 161);
  $pdf->Cell(40, 8, '$' . $cheque['descuento_seguro']);

  // Separador
  $pdf->Line(10, 175, 200, 175);

  /* --- Total percepciones --- */


  $pdf->SetXY(10, 171);
  $pdf->Ln();
  $pdf->Cell(40, 8, 'Total de percepciones:');

  // cantidad
  $percepciones = $cheque['sueldo_base'];
  $pdf->SetXY(55, 179);
  $pdf->Cell(40, 8, '$' . $percepciones);

  /* --- Total retenciones --- */
  $pdf->SetXY(10, 180);
  $pdf->Ln();
  $pdf->Cell(40, 8, 'Total de retenciones:');

  // cantidad
  $retenciones = $cheque['descuento_isr'] + $cheque['descuento_retiro'] + $cheque['descuento_vivienda'] + $cheque['descuento_seguro'];
  $pdf->SetXY(55, 188);
  $pdf->Cell(40, 8, '$' . $retenciones);

  $pdf->Line(53, 196, 80, 196);

  /* --- Pago --- */
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetXY(10, 190);
  $pdf->Ln();
  $pdf->Cell(40, 8, 'Pago:');

  // cantidad
  $pago_total = $percepciones - $retenciones;
  $pdf->SetXY(55, 198);
  $pdf->Cell(40, 8, '$' . $pago_total);

  // Cantidad en letra
  $str = get_cantidadLetra($pago_total);
  $pdf->SetXY(90, 198);
  $pdf->SetFont('Arial', '', 14);
  $pdf->Cell(40, 8, utf8_decode($str));
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->Text(10, 225, "Firma digital del emisor:");
  $pdf->Text(10, 235, $firma);
}
$pdf->Close();
$pdf->Output("cheques.pdf", 'I');
