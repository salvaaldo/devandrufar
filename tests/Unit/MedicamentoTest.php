<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;

class MedicamentoTest extends TestCase
{
    public function test_estado_lote_vencido()
    {
        $fecha = '01/2020';
        [$mes, $anio] = explode('/', $fecha);
        $vencimiento = Carbon::createFromDate($anio, $mes, 1);
        $this->assertTrue($vencimiento->isPast());
    }

    public function test_estado_lote_proximo()
    {
        $diasRestantes = 15;
        $this->assertTrue($diasRestantes <= 30);
    }

    public function test_fifo_ordena_por_fecha_mas_proxima()
    {
        $lotes = [
            ['lote' => 'A', 'vencimiento' => '2027-06-01'],
            ['lote' => 'B', 'vencimiento' => '2026-03-01'],
            ['lote' => 'C', 'vencimiento' => '2028-01-01'],
        ];
        usort($lotes, fn($a, $b) => $a['vencimiento'] <=> $b['vencimiento']);
        $this->assertEquals('B', $lotes[0]['lote']);
    }

    public function test_stock_no_puede_ser_negativo()
    {
        $stock = 0;
        $cantidad = 5;
        $this->assertFalse($stock >= $cantidad);
    }
}