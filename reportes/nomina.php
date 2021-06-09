<?php

require('./layout-nomina.php');
include '../includes/connection.php';
include '../includes/helpers.php';

if (isset($_GET['cant'])) {
    $fechaNomina = date('d/M/Y');
    $noTrabByPage = $_GET['cant'];
    $cheques = get_cheques($db);

    $query = "SELECT COUNT(idCheque) AS n FROM cheque";
    $result = mysqli_query($db, $query);
    $cant_cheques = mysqli_fetch_array($result)['n'];

    $j = 1;
    $folio = getdate()['mday'] . getdate()['mon'] . getdate()['year'] . '001';
    $suma = array(
        's_base' => 0,
        'isr' => 0,
        'retiro' => 0,
        'vivienda' => 0,
        'seguro' => 0,
        's_neto' => 0,
    );

    $pdf = new PDF();

    while ($j <= $cant_cheques) {


        $pdf->AddPage('LANDSCAPE', 'LETTER');
        $pdf->SetFont('Arial', '', 12);

        /* --- Numero de registro --- */
        $pdf->Cell(40, 5, 'Fecha de la nomina:');
        $pdf->Cell(20, 5, utf8_decode($fechaNomina));
        $pdf->Ln();

        /* --- Folio de nomina --- */
        $pdf->Cell(40, 8, 'Folio:');
        $pdf->Cell(20, 8, $folio);

        /* --- Numero de trabajador --- */
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetY(45);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(79, 78, 77);
        $pdf->Cell(10, 15, 'Reg', 0, 0, 'C', 1);
        $pdf->Cell(25, 15, 'No. Trabajador', 0, 0, 'C', 1);
        $pdf->Cell(35, 15, 'Nombre', 0, 0, 'C', 1);
        $pdf->Cell(25, 15, 'Hrs trabajadas', 0, 0, 'C', 1);
        $pdf->Cell(23, 15, 'Sueldo Base', 0, 0, 'C', 1);
        $pdf->Cell(25, 15, 'Desc. ISR', 0, 0, 'C', 1);
        $pdf->Cell(25, 15, 'Desc. Retiro', 0, 0, 'C', 1);
        $pdf->Cell(25, 15, 'Desc. Vivienda', 0, 0, 'C', 1);
        $pdf->Cell(22, 15, 'Seguro Social', 0, 0, 'C', 1);
        $pdf->Cell(23, 15, 'Sueldo Neto', 0, 0, 'C', 1);
        $pdf->Cell(20, 15, 'No. cheque', 0, 0, 'C', 1);
        $pdf->Ln();

        $pdf->SetLineWidth(0.5);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(80, 80, 80);
        $i = 1;
        while ($i <= $noTrabByPage && $j <= $cant_cheques) {
            $row = mysqli_fetch_array($cheques);
            $name = utf8_decode($row['apellido_paterno'] . " " . $row['apellido_materno'] . ", " . $row['nombre']);

            $pdf->Cell(10, 10, $j, 'B', 0, 'C', 1);
            $pdf->Cell(25, 10, $row['idTrabajador'], 'B', 0, 'C', 1);
            $pdf->Cell(35, 10, $name, 'B', 0, 'C', 1);
            $pdf->Cell(25, 10, $row['hrs_normales_trabajadas'], 'B', 0, 'C', 1);
            $pdf->Cell(23, 10, $row['sueldo_base'], 'B', 0, 'C', 1);
            $pdf->Cell(25, 10, $row['descuento_isr'], 'B', 0, 'C', 1);
            $pdf->Cell(25, 10, $row['descuento_retiro'], 'B', 0, 'C', 1);
            $pdf->Cell(25, 10, $row['descuento_vivienda'], 'B', 0, 'C', 1);
            $pdf->Cell(22, 10, $row['descuento_seguro'], 'B', 0, 'C', 1);
            $pdf->Cell(23, 10, $row['sueldo_neto'], 'B', 0, 'C', 1);
            $pdf->Cell(20, 10, $row['idCheque'], 'B', 0, 'C', 1);
            $pdf->Ln();

            $suma['s_base'] += $row['sueldo_base'];
            $suma['isr'] += $row['descuento_isr'];
            $suma['retiro'] += $row['descuento_retiro'];
            $suma['vivienda'] += $row['descuento_vivienda'];
            $suma['seguro'] += $row['descuento_seguro'];
            $suma['s_neto'] += $row['sueldo_neto'];
            $i++;
            $j++;
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln();
        $pdf->Cell(40, 7, "It is good to be with us.");
        $pdf->Ln();
        $pdf->Cell(40, 7, "Una vez pagado no hay cambios");
        $pdf->Ln();
        $pdf->Cell(40, 7, "Enjoy your salary");

        if ($j > $cant_cheques) {
            $pdf->AddPage('LANDSCAPE', 'LETTER');
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(120, 7, "Cantidad de Cheques Expedidos en la Nomina:");
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(20, 7, $cant_cheques);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(120, 7, "Suma Total de Sueldos Base:");
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(20, 7, '$' . $suma['s_base']);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(120, 7, "Suma Total de Descuentos por ISR:");
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(20, 7, '$' . $suma['isr']);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(120, 7, "Suma Total depositos a cuentas de Ahorro para el Retiro:");
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(20, 7, '$' . $suma['retiro']);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(120, 7, "Suma Total depositos a cuentas de Vivienda:");
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(20, 7, '$' . $suma['vivienda']);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(120, 7, "Suma Total depositos a cuentas de Seguro Social:");
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(20, 7, '$' . $suma['seguro']);
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(120, 7, "Suma Total de Sueldos Netos:");
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(20, 7, '$' . $suma['s_neto']);
        }
    }
    $pdf->Output();
}
