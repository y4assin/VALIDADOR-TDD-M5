<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IBANController extends Controller
{
    /**
     * Valida un IBAN (Número de Cuenta Bancaria Internacional).
     *
     * @param  string  $iban El IBAN a validar
     * @return bool Retorna verdadero si el IBAN es válido, falso si no lo es
     */
    public function validaIBAN($iban)
    {
        // Convertir a mayúsculas y eliminar espacios en blanco
        $iban = strtoupper(str_replace(' ', '', $iban));

        // Verificar si el IBAN cumple con el formato
        if (preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{4}[0-9]{7}([A-Z0-9]?){0,16}$/', $iban)) {
            // Extraer información del IBAN
            $country = substr($iban, 0, 2);
            $checksum = substr($iban, 2, 2);
            $ibanNumber = substr($iban, 4);

            // Ajustar el IBAN para el cálculo del checksum
            $ibanNumber .= ord($country[0]) - 55 . ord($country[1]) - 55 . substr($checksum, 0, 1) . substr($checksum, 1, 1);

            // Verificar el checksum utilizando la función bcmod
            if (bcmod($ibanNumber, '97') == 1) {
                return true; // El IBAN es válido
            }
        }
        return false; // El IBAN no es válido
    }

    /**
     * Valida un CCC (Código de Cuenta Corriente).
     *
     * @param  string  $ccc El CCC a validar
     * @return bool Retorna verdadero si el CCC es válido, falso si no lo es
     */
    function validaCCC($ccc)
    {
        // Eliminar espacios en blanco y guiones
        $ccc = str_replace([' ', '-'], '', $ccc);

        // Verificar la longitud del CCC (debe ser de 20 caracteres)
        if (strlen($ccc) !== 20) {
            return false;
        }

        // Verificar si el CCC contiene solo dígitos
        if (!ctype_digit($ccc)) {
            return false;
        }

        // Calcular y verificar el dígito de control
        $checksum = 0;
        foreach (str_split($ccc) as $index => $digit) {
            $checksum += (int) $digit * (10 - ($index % 10));
        }

        return $checksum % 11 === 0 && $checksum !== 0;
    }

    /**
     * Descubre el IBAN a partir de un IBAN con asteriscos.
     *
     * @param  string  $ibanConAsteriscos El IBAN con asteriscos
     * @return string|null Retorna el IBAN completo o null si el formato es incorrecto
     */
    public function descubreixIBAN($ibanConAsteriscos)
    {
        $ibanConAsteriscos = strtoupper(str_replace(' ', '', $ibanConAsteriscos));
        $longitud = strlen($ibanConAsteriscos);

        // Verificar que el formato proporcionado tenga al menos 4 caracteres al final para el dígito de control
        if ($longitud < 4) {
            return null; // Formato incorrecto
        }

        // Extraer los dos primeros caracteres para obtener el código del país
        $codigoPais = substr($ibanConAsteriscos, 0, 2);

        // Generar los asteriscos para completar el IBAN (excepto el código del país)
        $asteriscos = str_repeat('*', $longitud - 4);

        // Extraer los últimos dos caracteres para obtener el dígito de control
        $digitosControl = substr($ibanConAsteriscos, -2);

        // Combinar el código del país, los asteriscos y los dígitos de control para formar el IBAN completo
        $ibanCompleto = $codigoPais . $asteriscos . $digitosControl;

        // Devolver el IBAN completo
        return $ibanCompleto;
    }
}
