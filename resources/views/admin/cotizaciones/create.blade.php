@extends('layouts.app')

@section('title', 'Nueva Cotización')

@section('content')

    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('cotizaciones.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nueva Cotización</h1>
            <p class="text-sm text-gray-400 mt-0.5">Complete los datos y agregue los medicamentos</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ── Columna izquierda ── --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Datos cabecera --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 space-y-4">
                <h2 class="text-sm font-bold text-gray-700 border-b border-gray-100 pb-3">Datos de la Cotización</h2>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">N° Cotización</label>
                    <input type="text" id="numero" placeholder="Ej: COT-000001"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-mono text-gray-800 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Cliente <span
                            class="text-red-500">*</span></label>
                    <select id="cliente_id"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">— Seleccione cliente —</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Total --}}
            <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl shadow-md p-5 text-white">
                <p class="text-xs font-semibold opacity-75 uppercase tracking-wide">Total Cotización</p>
                <p class="text-3xl font-extrabold mt-1">Bs. <span id="totalGeneral">0.00</span></p>
                <p class="text-xs opacity-60 mt-1"><span id="totalItems">0</span> ítem(s) agregado(s)</p>
            </div>

            {{-- Guardar --}}
            <button onclick="guardarCotizacion()"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-2xl transition-all flex items-center justify-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Guardar Cotización
            </button>

        </div>

        {{-- ── Columna derecha ── --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Agregar medicamento --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 space-y-4">
                <h2 class="text-sm font-bold text-gray-700 border-b border-gray-100 pb-3">Agregar Medicamento</h2>

                {{-- Buscador --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Buscar Medicamento</label>
                    <select id="producto_select" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                        <option value="">— Buscar por nombre o código —</option>
                        @foreach ($productos as $p)
                            <option value="{{ $p->id }}" data-codigo="{{ $p->codigo }}"
                                data-nombre="{{ $p->nombre }}" data-concentracion="{{ $p->concentracion }}"
                                data-forma="{{ $p->forma_farmaceutica }}" data-origen="{{ $p->origen }}"
                                data-marca="{{ $p->marca }}" data-precio="{{ $p->precio_referencial }}">
                                [{{ $p->codigo }}] {{ $p->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Info del producto --}}
                <div id="infoProducto" class="hidden bg-gray-50 border border-gray-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Información del Medicamento</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                        <div class="flex gap-1"><span class="font-semibold text-gray-700">Código:</span><span
                                id="info_codigo"></span></div>
                        <div class="flex gap-1"><span class="font-semibold text-gray-700">Concentración:</span><span
                                id="info_concentracion"></span></div>
                        <div class="flex gap-1"><span class="font-semibold text-gray-700">Forma:</span><span
                                id="info_forma"></span></div>
                        <div class="flex gap-1"><span class="font-semibold text-gray-700">Origen:</span><span
                                id="info_origen"></span></div>
                        <div class="flex gap-1"><span class="font-semibold text-gray-700">Marca:</span><span
                                id="info_marca"></span></div>
                        <div class="flex gap-1"><span class="font-semibold text-gray-700">Stock total:</span>
                            <span id="info_stock" class="font-bold text-emerald-600">—</span>
                        </div>
                    </div>
                </div>

                {{-- Lote PEPS automático --}}
                <div id="seccionLote" class="hidden bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-blue-700 uppercase tracking-wide mb-3">
                        Lote Asignado Automáticamente (PEPS)
                    </p>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-white rounded-lg p-3 border border-blue-100 text-center">
                            <p class="text-xs text-gray-400 mb-1">Lote</p>
                            <p class="text-sm font-bold text-gray-800" id="lote_peps_label">—</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-blue-100 text-center">
                            <p class="text-xs text-gray-400 mb-1">Vence</p>
                            <p class="text-sm font-bold text-amber-600" id="vence_peps_label">—</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-blue-100 text-center">
                            <p class="text-xs text-gray-400 mb-1">Disponible</p>
                            <p class="text-sm font-bold text-emerald-600" id="stock_peps_label">—</p>
                        </div>
                    </div>
                    <p class="text-xs text-blue-500 mt-2">
                        ✓ El sistema selecciona el lote con fecha de vencimiento más próxima
                    </p>
                    <input type="hidden" id="lote_peps_valor">
                    <input type="hidden" id="inventario_peps_id">
                </div>

                {{-- Cantidad y precio --}}
                <div id="seccionCantidad" class="hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                                Cantidad <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="cantidad" min="1" placeholder="0"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                                oninput="calcularSubtotal()">
                            <p class="text-xs text-gray-400 mt-1">
                                Disponible: <span id="stockDisponible" class="font-semibold text-emerald-600">—</span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                                Precio Unitario (Bs.) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="precio_unitario" min="0" step="0.01" placeholder="0.00"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                                oninput="calcularSubtotal()">
                        </div>
                    </div>
                </div>

                {{-- Subtotal --}}
                <div id="subtotalPreview"
                    class="hidden bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 flex justify-between items-center">
                    <span class="text-sm font-semibold text-emerald-700">Subtotal:</span>
                    <span class="text-xl font-extrabold text-emerald-800">Bs. <span id="subtotalValor">0.00</span></span>
                </div>

                <button onclick="agregarItem()"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar a la lista
                </button>
            </div>

            {{-- Tabla items --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-700">Lista de Medicamentos</h2>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-lg" id="contadorItems">0 ítems</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-3 py-3 text-left text-gray-500 font-semibold uppercase tracking-wide">N°</th>
                                <th class="px-3 py-3 text-left text-gray-500 font-semibold uppercase tracking-wide">Código
                                </th>
                                <th class="px-3 py-3 text-left text-gray-500 font-semibold uppercase tracking-wide">
                                    Medicamento</th>
                                <th class="px-3 py-3 text-left text-gray-500 font-semibold uppercase tracking-wide">
                                    Concentración</th>
                                <th class="px-3 py-3 text-left text-gray-500 font-semibold uppercase tracking-wide">Forma
                                </th>
                                <th class="px-3 py-3 text-left text-gray-500 font-semibold uppercase tracking-wide">Lote
                                    (PEPS)</th>
                                <th class="px-3 py-3 text-right text-gray-500 font-semibold uppercase tracking-wide">Cant.
                                </th>
                                <th class="px-3 py-3 text-right text-gray-500 font-semibold uppercase tracking-wide">P.
                                    Unit.</th>
                                <th class="px-3 py-3 text-right text-gray-500 font-semibold uppercase tracking-wide">P.
                                    Total</th>
                                <th class="px-3 py-3 text-center text-gray-500 font-semibold">—</th>
                            </tr>
                        </thead>
                        <tbody id="tablaItems" class="divide-y divide-gray-50">
                            <tr id="filaVacia">
                                <td colspan="10" class="px-3 py-10 text-center text-gray-300">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Aún no hay medicamentos en la lista
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            let items = [];
            let lotePepsActual = null;

            $(document).ready(function() {
                $('#producto_select').select2({
                    placeholder: '— Buscar por nombre o código —',
                    width: '100%'
                });
                $('#cliente_id').select2({
                    placeholder: '— Seleccione cliente —',
                    width: '100%'
                });

                $('#producto_select').on('change', function() {
                    const opt = this.options[this.selectedIndex];
                    if (!this.value) {
                        resetFormProducto();
                        return;
                    }

                    // Mostrar info del producto
                    document.getElementById('info_codigo').textContent = opt.dataset.codigo;
                    document.getElementById('info_concentracion').textContent = opt.dataset.concentracion ||
                        '—';
                    document.getElementById('info_forma').textContent = opt.dataset.forma || '—';
                    document.getElementById('info_origen').textContent = opt.dataset.origen || '—';
                    document.getElementById('info_marca').textContent = opt.dataset.marca || '—';
                    document.getElementById('info_stock').textContent = 'Cargando...';
                    document.getElementById('infoProducto').classList.remove('hidden');
                    document.getElementById('precio_unitario').value = opt.dataset.precio || '';

                    // Cargar lote PEPS automáticamente
                    fetch(`{{ route('cotizaciones.lotes') }}?producto_id=${this.value}`)
                        .then(r => r.json())
                        .then(lotes => {
                            if (!lotes.length) {
                                document.getElementById('info_stock').textContent = '0 uds.';
                                document.getElementById('seccionLote').classList.add('hidden');
                                document.getElementById('seccionCantidad').classList.add('hidden');
                                document.getElementById('subtotalPreview').classList.add('hidden');
                                alert('No hay stock disponible para este medicamento.');
                                return;
                            }

                            // El primero es el PEPS (vence primero, orderBy fecha_vencimiento asc)
                            const peps = lotes[0];
                            lotePepsActual = peps;

                            // Calcular stock total
                            const stockTotal = lotes.reduce((s, l) => s + l.cantidad, 0);
                            document.getElementById('info_stock').textContent = stockTotal + ' uds.';

                            // Mostrar lote PEPS
                            document.getElementById('lote_peps_label').textContent = peps.lote;
                            document.getElementById('vence_peps_label').textContent = formatFecha(peps
                                .fecha_vencimiento);
                            document.getElementById('stock_peps_label').textContent = peps.cantidad +
                                ' uds.';
                            document.getElementById('lote_peps_valor').value = peps.lote;
                            document.getElementById('inventario_peps_id').value = peps.id;
                            document.getElementById('stockDisponible').textContent = stockTotal + ' uds.';
                            document.getElementById('cantidad').max = stockTotal;

                            document.getElementById('seccionLote').classList.remove('hidden');
                            document.getElementById('seccionCantidad').classList.remove('hidden');
                            document.getElementById('subtotalPreview').classList.remove('hidden');
                        });
                });
            });

            function formatFecha(fechaStr) {
                if (!fechaStr) return '—';
                const d = new Date(fechaStr);
                return d.toLocaleDateString('es-BO', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            function calcularSubtotal() {
                const qty = parseFloat(document.getElementById('cantidad').value) || 0;
                const price = parseFloat(document.getElementById('precio_unitario').value) || 0;
                document.getElementById('subtotalValor').textContent = (qty * price).toFixed(2);
            }

            function agregarItem() {
                const select = document.getElementById('producto_select');
                const opt = select.options[select.selectedIndex];
                const cantidad = parseInt(document.getElementById('cantidad').value);
                const precio = parseFloat(document.getElementById('precio_unitario').value);
                const stock = parseInt(document.getElementById('cantidad').max) || 0;
                const lote = document.getElementById('lote_peps_valor').value || 'S/L';
                const invId = document.getElementById('inventario_peps_id').value || null;

                if (!select.value) {
                    alert('Seleccione un medicamento.');
                    return;
                }
                if (!cantidad || cantidad < 1) {
                    alert('Ingrese una cantidad válida.');
                    return;
                }
                if (cantidad > stock) {
                    alert(`Stock insuficiente. Disponible: ${stock} uds.`);
                    return;
                }
                if (!precio || precio <= 0) {
                    alert('Ingrese un precio válido.');
                    return;
                }

                const nro = items.length + 1;
                const total = cantidad * precio;

                items.push({
                    nro,
                    producto_id: select.value,
                    inventario_id: invId,
                    codigo: opt.dataset.codigo,
                    nombre: opt.dataset.nombre,
                    concentracion: opt.dataset.concentracion || '—',
                    forma: opt.dataset.forma || '—',
                    origen: opt.dataset.origen || '—',
                    marca: opt.dataset.marca || '—',
                    lote,
                    cantidad,
                    precio_unitario: precio,
                    precio_total: total,
                });

                renderTabla();
                resetFormProducto();
            }

            function eliminarItem(nro) {
                items = items.filter(i => i.nro !== nro);
                items.forEach((i, idx) => i.nro = idx + 1);
                renderTabla();
            }

            function renderTabla() {
                const tbody = document.getElementById('tablaItems');

                if (!items.length) {
                    tbody.innerHTML = `<tr id="filaVacia"><td colspan="10" class="px-3 py-10 text-center text-gray-300">
            <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Aún no hay medicamentos en la lista</td></tr>`;
                    document.getElementById('totalGeneral').textContent = '0.00';
                    document.getElementById('totalItems').textContent = '0';
                    document.getElementById('contadorItems').textContent = '0 ítems';
                    return;
                }

                tbody.innerHTML = items.map(i => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-3 py-2.5 font-bold text-gray-400">${i.nro}</td>
            <td class="px-3 py-2.5 font-mono text-blue-600 font-semibold">${i.codigo}</td>
            <td class="px-3 py-2.5 font-semibold text-gray-800">${i.nombre}</td>
            <td class="px-3 py-2.5 text-gray-500">${i.concentracion}</td>
            <td class="px-3 py-2.5 text-gray-500">${i.forma}</td>
            <td class="px-3 py-2.5">
                <span class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded-md text-xs">${i.lote}</span>
            </td>
            <td class="px-3 py-2.5 text-right font-bold text-gray-800">${i.cantidad}</td>
            <td class="px-3 py-2.5 text-right text-gray-600">Bs. ${i.precio_unitario.toFixed(2)}</td>
            <td class="px-3 py-2.5 text-right font-extrabold text-emerald-700">Bs. ${i.precio_total.toFixed(2)}</td>
            <td class="px-3 py-2.5 text-center">
                <button onclick="eliminarItem(${i.nro})" class="text-red-400 hover:text-red-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </td>
        </tr>
    `).join('');

                const totalGral = items.reduce((s, i) => s + i.precio_total, 0);
                document.getElementById('totalGeneral').textContent = totalGral.toFixed(2);
                document.getElementById('totalItems').textContent = items.length;
                document.getElementById('contadorItems').textContent = items.length + ' ítem(s)';
            }

            function resetFormProducto() {
                $('#producto_select').val('').trigger('change.select2');
                document.getElementById('infoProducto').classList.add('hidden');
                document.getElementById('seccionLote').classList.add('hidden');
                document.getElementById('seccionCantidad').classList.add('hidden');
                document.getElementById('subtotalPreview').classList.add('hidden');
                document.getElementById('cantidad').value = '';
                document.getElementById('precio_unitario').value = '';
                document.getElementById('subtotalValor').textContent = '0.00';
                document.getElementById('stockDisponible').textContent = '—';
                document.getElementById('lote_peps_valor').value = '';
                document.getElementById('inventario_peps_id').value = '';
                lotePepsActual = null;
            }

            function guardarCotizacion() {
                const numero = document.getElementById('numero').value.trim();
                const cliente_id = document.getElementById('cliente_id').value;

                if (!numero) {
                    alert('El número de cotización es requerido.');
                    return;
                }
                if (!cliente_id) {
                    alert('Seleccione un cliente.');
                    return;
                }
                if (!items.length) {
                    alert('Agregue al menos un medicamento.');
                    return;
                }

                fetch('{{ route('cotizaciones.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            numero,
                            cliente_id,
                            items
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = `/cotizaciones/${data.id}`;
                        } else {
                            alert('Error: ' + (data.error || JSON.stringify(data.errors)));
                        }
                    })
                    .catch(err => alert('Error de conexión: ' + err.message));
            }
        </script>
    @endpush

@endsection
