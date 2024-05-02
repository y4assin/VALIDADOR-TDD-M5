<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\IBANController;

class ValidadorIBANTest extends TestCase
{
    /** @test */
    public function puede_validar_un_IBAN_correcto()
    {
        // Creamos una instancia del validador IBAN

        $ibanController = new IBANController();
        /// Definimos un IBAN válido para la prueba
        $ibanValido = 'ES9121000418450200051332';
        /// Llamamos al método validar del validador IBAN con el IBAN válido
        $this->assertTrue($ibanController->validaIBAN($ibanValido));
    }

    /** @test */
    public function puede_invalidar_un_IBAN_incorrecto()
    {   // Creamos una instancia del validador IBAN
        $ibanController = new IBANController();
        // Definimos un IBAN válido para la prueba
        $ibanInvalido = 'ES1234567890123456789012';
        // Llamamos al método validar del validador IBAN con el IBAN válido
        $this->assertFalse($ibanController->validaIBAN($ibanInvalido));
    }

    /** @test */
    public function puede_validar_un_CCC_correcto()
    {   // Creamos una instancia del validador CCC
        $ibanController = new IBANController();
        // Definimos un CCC valido para la prueba
        $cccValido = '01234567890123456789';
        // llamamo al metodo validador CCC para hacer la prueba
        $this->assertTrue($ibanController->validaCCC($cccValido));
    }

    /** @test */
    public function puede_invalidar_un_CCC_incorrecto()
    {    // Creamos una instancia del validador CCC
        $ibanController = new IBANController();
        // Definimos un CCC inválido para la prueba
        $cccInvalido = '12345678901234567890';
        // llamamo al metodo validador CCC para hacer la prueba
        $this->assertFalse($ibanController->validaCCC($cccInvalido));
    }

    /** @test */
    public function puede_descubrir_el_IBAN_a_partir_de_un_IBAN_con_asteriscos()
    {   // Creamos una instancia del descubridor de IBAN
        $ibanController = new IbanController();
        // Definimos un IBAN con asteriscos para la prueba
        $ibanConAsteriscos = 'ES**************20';
        // Llamamos al método descubrir del descubridor de IBAN con el IBAN con asteriscos
        $resultado = $ibanController->descubreixIBAN($ibanConAsteriscos);
        // Verificamos que el resultado de la función no sea nulo
        $this->assertNotNull($resultado);
    }
}
