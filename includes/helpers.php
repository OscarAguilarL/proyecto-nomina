<?php

/**
 * Escapa caracteres especiales de un string
 * @param mysqli $db Objecto de conexión a la base de datos
 * @param string $str Cadena de texto que se va a escapar
 * @return string
 */
function escape_string($db, $str)
{
    return strip_tags(mysqli_escape_string($db, $str));
}

/**
 * Verifica que una cadena de texto sea una fecha
 * @param string $date fecha que se va a verificar
 * @return string
 */
function verify_date($date)
{
    return preg_match('/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/', $date) ? $date : 'No aplica';
}

/**
 * Trae todos los trabajadores de la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @return mysqli_result
 */
function get_trabajadores($db)
{
    $query = "SELECT * FROM trabajador ORDER BY apellido_paterno ASC";
    $result_user = mysqli_query($db, $query);
    return $result_user;
}

/**
 * Trae un trabajador eespecifico de la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param int $id id del trabajador que se busca
 * @return mysqli_result
 */
function get_trabajador($db, $id)
{
    $query = "SELECT * FROM trabajador WHERE idTrabajador = $id";
    $result_trabajador = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result_trabajador);
}

/**
 * Busca el sueldo que tiene un trabajador en la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param int $id id del trabajador que se busca
 * @return float
 */
function get_salariosTrabajador($db, $id)
{
    $query = "
    SELECT salario_hora_base,salario_hora_extra FROM puesto WHERE idPuesto IN (
	    SELECT Puesto_idPuesto FROM trabajador WHERE idTrabajador = $id
    )
  ";
    $result = mysqli_query($db, $query);
    $cantidad = mysqli_fetch_array($result);
    return $cantidad;
}

/**
 * Trae todos los puestos de la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @return mysqli_result
 */
function get_puestos($db)
{
    $query = "SELECT * FROM puesto";
    $result = mysqli_query($db, $query);
    return $result;
}

/**
 * Trae un puesto especifico de la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param int $id id del puesto que se busca
 * @return string[]
 */
function get_puesto($db, $id)
{
    $query = "SELECT * FROM puesto WHERE idPuesto = $id";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result);
}

/**
 * Trae la vista de nomina de la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @return mysqli_result
 */
function get_cheques($db)
{
    $query = "SELECT * FROM trabajador,cheque
            WHERE cheque.Trabajador_idTrabajador = trabajador.idTrabajador
            ORDER BY apellido_paterno ASC";
    $result = mysqli_query($db, $query);
    return $result;
}

/**
 * Trae una nomina en especifico de la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param int $id id de la nomina que se busca
 * @return string[]
 */
function get_cheque($db, $idCheque)
{
    $query = "SELECT * FROM trabajador,cheque
            WHERE cheque.Trabajador_idTrabajador = trabajador.idTrabajador
            AND idCheque = $idCheque";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result);
}

/**
 * Trae el registro de configuracion de nomina de la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @return mysqli_result
 */
function get_configuracion($db)
{
    $query = "SELECT * FROM configuracion LIMIT 1";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result);
}

/**
 * Calcula el sueldo base de un trabajador
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param int $horas_normales horas normales trabajadas
 * @param int $horas_extra horas extra trabajadas
 * @param int $id id del trabajador que se busca
 */
function calc_sueldoBase($db, $horas_normales, $horas_extra, $idTrabajador): float
{
    $sueldo_trabajador = get_salariosTrabajador($db, $idTrabajador);
    $pago = $horas_normales * $sueldo_trabajador['salario_hora_base'] + $horas_extra * $sueldo_trabajador['salario_hora_extra'];
    return $pago;
}

/**
 * Calculo del descuento de ISR basado
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param float $SueldoBase Sueldo Base del trabajador calculado
 * @
 */
function calc_descuentoISR($db, $sueldoBase)
{
    $configuracion = get_configuracion($db);
    if ($sueldoBase <= $configuracion['limite_isr']) {
        $isr = $sueldoBase * $configuracion['isr_min'] / 100;
    } else {
        $isr = $sueldoBase * $configuracion['isr_max'] / 100;
    }
    return $isr;
}

/**
 * Calcula el descuento de abono para el retiro del trabajador
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param float $SueldoBase Sueldo Base del trabajador calculado
 */
function calc_descuentoRetiro($db, $sueldoBase)
{
    $configuracion = get_configuracion($db);
    $descuento = $sueldoBase * $configuracion['ahorro_retiro'] / 100;
    return $descuento;
}

/**
 * Calcula el descuento para vivienda del trabajador
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param float $SueldoBase Sueldo Base del trabajador calculado
 */
function calc_descuentoVivienda($db, $sueldoBase)
{
    $configuracion = get_configuracion($db);
    $descuento = $sueldoBase * $configuracion['vivienda'] / 100;
    return $descuento;
}

/**
 * Calcula el descuento para seguro medico del trabajador
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param float $SueldoBase Sueldo Base del trabajador calculado
 */
function calc_descuentoSeguro($db, $sueldoBase)
{
    $configuracion = get_configuracion($db);
    $descuento = $sueldoBase * $configuracion['seguro_social'] / 100;
    return $descuento;
}

/**
 * Trae la vista de cheques de la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @return mysqli_result
 */
function get_vistaCheques($db)
{
    $query = "SELECT * FROM vista_cheque";
    $result = mysqli_query($db, $query);
    return $result;
}

/**
 * Trae una nomina en especifico de la base de datos
 * @param mysqli $db Objeto de conexión a la base de datos
 * @param int $id id de la nomina que se busca
 * @return string[]
 */
function get_vistaCheque($db, $id)
{
    $query = "SELECT * FROM vista_cheque WHERE idCheque = $id";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result);
}

/**
 * Devuelve una firma digital
 * @return string|false
 */
function get_firmaDigital()
{
    return hash("sha256", "Instituto Tecnologico de Pachuca");
}

/**
 * Devuelve una cantidad escrita con letra
 * @param float $cant cantidad a convertir
 * @return string
 */
function get_cantidadLetra($cant)
{
    $formatterES = new NumberFormatter("es-MX", NumberFormatter::SPELLOUT);
    // las siguientes dos lineas son para que escriba bien despues del punto
    $izquierda = intval(floor($cant));
    $derecha = intval(($cant - floor($cant)) * 100);
    // Se concatena las dos partes de la cantidad
    return ucfirst($formatterES->format($izquierda) . " pesos y " . $formatterES->format($derecha) . " centavos");
}
