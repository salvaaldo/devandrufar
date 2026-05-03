@extends('layouts.app')

@section('title', 'Nueva Cotización')

@section('content')
    <div class="p-6">

        {{-- Encabezado --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('cotizaciones.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Nueva Cotización</h1>
                <p class="text-sm text-gray-500">Complete los datos y agregue los medicamentos</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Columna izquierda: datos cabecera --}}
            <div class="lg:col-span-1 space-y-4">

                {{-- Datos de la cotización --}}
                <div class="bg-white rounded-xl shadow p-5 space-y-4">
                    <h2 class="font-semibold text-gray-700 border-b pb-2">Datos de la Cotización</h2>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">N° Cotización</label>
                        <input type="text" id="numero" placeholder="Ej: COT-000001"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Cliente <span
                                class="text-red-500">*</span></label>
                        <select id="cliente_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Seleccione cliente --</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Total --}}
                <div class="bg-blue-600 rounded-xl shadow p-5 text-white">
                    <p class="text-sm font-medium opacity-80">Total Cotización</p>
                    <p class="text-3xl font-bold mt-1">Bs. <span id="totalGeneral">0.00</span></p>
                    <p class="text-xs opacity-70 mt-1"><span id="totalItems">0</span> ítem(s) agregado(s)</p>
                </div>

                {{-- Botón guardar --}}
                <button onclick="guardarCotizacion()"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Cotización
                </button>
            </div>

            {{-- Columna derecha: agregar productos --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Buscador de producto --}}
                <div class="bg-white rounded-xl shadow p-5 space-y-4">
                    <h2 class="font-semibold text-gray-700 border-b pb-2">Agregar Medicamento</h2>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Buscar Medicamento</label>
                        <select id="producto_select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">-- Buscar por nombre o código --</option>
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
                    <div id="infoProducto"
                        class="hidden bg-gray-50 rounded-lg p-3 text-xs text-gray-600 grid grid-cols-2 gap-2">
                        <div><span class="font-medium">Código:</span> <span id="info_codigo"></span></div>
                        <div><span class="font-medium">Concentración:</span> <span id="info_concentracion"></span></div>
                        <div><span class="font-medium">Forma:</span> <span id="info_forma"></span></div>
                        <div><span class="font-medium">Origen:</span> <span id="info_origen"></span></div>
                        <div><span class="font-medium">Marca:</span> <span id="info_marca"></span></div>
                        <div>
                            <span class="font-medium">Stock disponible:</span>
                            <span id="info_stock" class="font-bold text-green-600">—</span>
                        </div>
                    </div>

                    {{-- Cantidad, lote y precio --}}
                    <div id="seccionCantidad" class="hidden space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cantidad <span
                                        class="text-red-500">*</span></label>
                                <input type="number" id="cantidad" min="1" placeholder="0"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                    oninput="calcularSubtotal()">
                                <p class="text-xs text-gray-400 mt-1">Disponible: <span id="stockDisponible"
                                        class="font-medium text-green-600">—</span></p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Precio Unitario (Bs.) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" id="precio_unitario" min="0" step="0.01"
                                    placeholder="0.00"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                    oninput="calcularSubtotal()">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Lote (referencial,
                                opcional)</label>
                            <input type="text" id="lote_input_manual" placeholder="Ej: 230706"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    {{-- Subtotal --}}
                    <div id="subtotalPreview"
                        class="hidden bg-blue-50 rounded-lg px-4 py-2 flex justify-between items-center">
                        <span class="text-sm text-blue-700 font-medium">Subtotal:</span>
                        <span class="text-lg font-bold text-blue-800">Bs. <span id="subtotalValor">0.00</span></span>
                    </div>

                    <button onclick="agregarItem()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Agregar a la lista
                    </button>

                    {{-- Info del producto seleccionado --}}
                    <div id="infoProducto"
                        class="hidden bg-gray-50 rounded-lg p-3 text-xs text-gray-600 grid grid-cols-2 gap-2">
                        <div><span class="font-medium">Código:</span> <span id="info_codigo"></span></div>
                        <div><span class="font-medium">Concentración:</span> <span id="info_concentracion"></span></div>
                        <div><span class="font-medium">Forma:</span> <span id="info_forma"></span></div>
                        <div><span class="font-medium">Origen:</span> <span id="info_origen"></span></div>
                        <div><span class="font-medium">Marca:</span> <span id="info_marca"></span></div>
                    </div>

                    {{-- Selector de lote --}}
                    {{-- Selector de lote MODIFICADO --}}
                    <div id="seccionLotes" class="hidden">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Lote (Escribe o selecciona)</label>

                        <input type="text" id="lote_input_manual"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                            placeholder="Escribe el número de lote..." list="lotes_sugeridos">

                        <datalist id="lotes_sugeridos"></datalist>

                        {{-- Mantenemos este div vacío para que no te dé error si el JS lo busca --}}
                        <div id="lotesContainer" class="hidden"></div>
                    </div>

                    {{-- Cantidad y precio --}}
                    <div id="seccionCantidad" class="hidden grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cantidad <span
                                    class="text-red-500">*</span></label>
                            <input type="number" id="cantidad" min="1" placeholder="0"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                oninput="calcularSubtotal()">
                            <p class="text-xs text-gray-400 mt-1">Disponible: <span id="stockDisponible"
                                    class="font-medium text-green-600">—</span></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Precio Unitario (Bs.) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" id="precio_unitario" min="0" step="0.01" placeholder="0.00"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                oninput="calcularSubtotal()">
                        </div>
                    </div>

                    {{-- Subtotal preview --}}
                    <div id="subtotalPreview"
                        class="hidden bg-blue-50 rounded-lg px-4 py-2 flex justify-between items-center">
                        <span class="text-sm text-blue-700 font-medium">Subtotal:</span>
                        <span class="text-lg font-bold text-blue-800">Bs. <span id="subtotalValor">0.00</span></span>
                    </div>

                    
                </div>

                {{-- Tabla de ítems --}}
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-700">Lista de Medicamentos</h2>
                        <span class="text-xs text-gray-400" id="contadorItems">0 ítems</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-gray-500 font-semibold">N°</th>
                                    <th class="px-3 py-2 text-left text-gray-500 font-semibold">Código</th>
                                    <th class="px-3 py-2 text-left text-gray-500 font-semibold">Medicamento</th>
                                    <th class="px-3 py-2 text-left text-gray-500 font-semibold">Concentración</th>
                                    <th class="px-3 py-2 text-left text-gray-500 font-semibold">Forma</th>
                                    <th class="px-3 py-2 text-left text-gray-500 font-semibold">Origen</th>
                                    <th class="px-3 py-2 text-left text-gray-500 font-semibold">Marca</th>
                                    <th class="px-3 py-2 text-left text-gray-500 font-semibold">Lote</th>
                                    <th class="px-3 py-2 text-right text-gray-500 font-semibold">Cantidad</th>
                                    <th class="px-3 py-2 text-right text-gray-500 font-semibold">P. Unit.</th>
                                    <th class="px-3 py-2 text-right text-gray-500 font-semibold">P. Total</th>
                                    <th class="px-3 py-2 text-center text-gray-500 font-semibold">—</th>
                                </tr>
                            </thead>
                            <tbody id="tablaItems" class="divide-y divide-gray-100">
                                <tr id="filaVacia">
                                    <td colspan="12" class="px-3 py-8 text-center text-gray-400">
                                        Aún no hay medicamentos en la lista
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let items = [];

            $(document).ready(function() {
                $('#producto_select').select2({
                    placeholder: '-- Buscar por nombre o código --',
                    width: '100%'
                });
                $('#cliente_id').select2({
                    placeholder: '-- Seleccione cliente --',
                    width: '100%'
                });

                $('#producto_select').on('change', function() {
                    const opt = this.options[this.selectedIndex];
                    if (!this.value) {
                        resetFormProducto();
                        return;
                    }

                    document.getElementById('info_codigo').textContent = opt.dataset.codigo;
                    document.getElementById('info_concentracion').textContent = opt.dataset.concentracion ||
                    '—';
                    document.getElementById('info_forma').textContent = opt.dataset.forma || '—';
                    document.getElementById('info_origen').textContent = opt.dataset.origen || '—';
                    document.getElementById('info_marca').textContent = opt.dataset.marca || '—';
                    document.getElementById('info_stock').textContent = 'Cargando...';
                    document.getElementById('infoProducto').classList.remove('hidden');
                    document.getElementById('precio_unitario').value = opt.dataset.precio || '';
                    document.getElementById('seccionCantidad').classList.remove('hidden');
                    document.getElementById('subtotalPreview').classList.remove('hidden');

                    // Cargar stock total
                    fetch(`{{ route('cotizaciones.stock') }}?producto_id=${this.value}`)
                        .then(r => r.json())
                        .then(data => {
                            document.getElementById('info_stock').textContent = data.stock + ' uds.';
                            document.getElementById('stockDisponible').textContent = data.stock + ' uds.';
                            document.getElementById('cantidad').max = data.stock;
                        });
                });
            });

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
                const lote = document.getElementById('lote_input_manual').value || 'S/L';
                const stock = parseInt(document.getElementById('cantidad').max) || 0;

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
                    inventario_id: null,
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
                    tbody.innerHTML =
                        `<tr id="filaVacia"><td colspan="12" class="px-3 py-8 text-center text-gray-400">Aún no hay medicamentos en la lista</td></tr>`;
                    document.getElementById('totalGeneral').textContent = '0.00';
                    document.getElementById('totalItems').textContent = '0';
                    document.getElementById('contadorItems').textContent = '0 ítems';
                    return;
                }

                tbody.innerHTML = items.map(i => `
        <tr class="hover:bg-gray-50">
            <td class="px-3 py-2 font-semibold text-gray-500">${i.nro}</td>
            <td class="px-3 py-2 font-mono text-blue-700">${i.codigo}</td>
            <td class="px-3 py-2 text-gray-800 font-medium">${i.nombre}</td>
            <td class="px-3 py-2 text-gray-600">${i.concentracion}</td>
            <td class="px-3 py-2 text-gray-600">${i.forma}</td>
            <td class="px-3 py-2 text-gray-600">${i.origen}</td>
            <td class="px-3 py-2 text-gray-600">${i.marca}</td>
            <td class="px-3 py-2 text-gray-600">${i.lote}</td>
            <td class="px-3 py-2 text-right font-semibold">${i.cantidad}</td>
            <td class="px-3 py-2 text-right">Bs. ${i.precio_unitario.toFixed(2)}</td>
            <td class="px-3 py-2 text-right font-bold text-green-700">Bs. ${i.precio_total.toFixed(2)}</td>
            <td class="px-3 py-2 text-center">
                <button onclick="eliminarItem(${i.nro})" class="text-red-400 hover:text-red-600">
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
                document.getElementById('seccionCantidad').classList.add('hidden');
                document.getElementById('subtotalPreview').classList.add('hidden');
                document.getElementById('cantidad').value = '';
                document.getElementById('precio_unitario').value = '';
                document.getElementById('lote_input_manual').value = '';
                document.getElementById('subtotalValor').textContent = '0.00';
                document.getElementById('stockDisponible').textContent = '—';
            }

            function guardarCotizacion() {
                const numero = document.getElementById('numero').value.trim();
                const cliente_id = document.getElementById('cliente_id').value;

                if (!numero) {
                    alert('Ingrese el número de cotización.');
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
                    .catch(error => alert('Error de conexión: ' + error.message));
            }
        </script>
    @endpush
@endsection
