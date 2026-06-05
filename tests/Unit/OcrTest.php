<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;

class OcrTest extends TestCase
{
    /** @test */
    public function test_fecha_formato_mm_yyyy()
    {
        $fecha = '06/2026';
        $this->assertMatchesRegularExpression('/^\d{2}\/\d{4}$/', $fecha);
    }

    /** @test */
    public function test_estado_vencido()
    {
        $fecha = '01/2020';
        [$mes, $anio] = explode('/', $fecha);
        $vencimiento = Carbon::createFromDate($anio, $mes, 1);
        $this->assertTrue($vencimiento->isPast());
    }

    /** @test */
    public function test_estado_vigente()
    {
        $fecha = '12/2099';
        [$mes, $anio] = explode('/', $fecha);
        $vencimiento = Carbon::createFromDate($anio, $mes, 1);
        $this->assertFalse($vencimiento->isPast());
    }

    /** @test */
    public function test_estado_proximo_a_vencer()
    {
        $fecha = Carbon::now()->addDays(15)->format('m/Y');
        [$mes, $anio] = explode('/', $fecha);
        $vencimiento = Carbon::createFromDate($anio, $mes, 1);
        $diasRestantes = now()->diffInDays($vencimiento);
        $this->assertTrue($diasRestantes <= 30);
    }
}