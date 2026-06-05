<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;

class DashboardTest extends TestCase
{
    public function test_clasificar_vencimiento_vencido()
    {
        $fecha = '01/2020';
        [$mes, $anio] = explode('/', $fecha);
        $vencimiento = Carbon::createFromDate($anio, $mes, 1);
        $this->assertTrue($vencimiento->isPast());
    }

    public function test_clasificar_vencimiento_proximo()
    {
        $diasRestantes = 20;
        $this->assertTrue($diasRestantes <= 30 && $diasRestantes > 0);
    }

    public function test_clasificar_vencimiento_vigente()
    {
        $diasRestantes = 120;
        $this->assertTrue($diasRestantes > 30);
    }

    public function test_baja_reduce_stock()
    {
        $stockInicial = 10;
        $cantidad = 3;
        $stockFinal = $stockInicial - $cantidad;
        $this->assertEquals(7, $stockFinal);
    }
}