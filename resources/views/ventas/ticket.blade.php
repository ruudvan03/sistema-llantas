<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket {{ $venta->folio }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center print:bg-white font-mono text-black">
    
    <div class="w-[80mm] bg-white p-4 text-[12px] shadow-md mt-4 print:w-full print:p-0 print:shadow-none print:mt-0 print:border-none">

        <button onclick="window.print()" class="w-full py-3 mb-3 bg-emerald-500 text-white font-bold rounded print:hidden hover:bg-emerald-600 transition">
            Imprimir Ticket
        </button>

        <div class="text-center font-bold text-base mb-1">
            LLANTAS ECONÓMICAS
        </div>
        <div class="text-center mb-3 leading-tight">
            Sistema de Punto de Venta<br>
            Sucursal: {{ $venta->sucursal_id }}
        </div>

        <div class="border-t border-dashed border-black my-2"></div>

        <div class="flex justify-between mb-0.5">
            <span>Folio:</span>
            <span class="font-bold">{{ $venta->folio }}</span>
        </div>
        <div class="flex justify-between mb-0.5">
            <span>Fecha:</span>
            <span>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex justify-between mb-0.5">
            <span>Cajero ID:</span>
            <span>{{ $venta->usuario_id }}</span>
        </div>
        <div class="flex justify-between mb-0.5">
            <span>Cliente:</span>
            <span class="truncate ml-2">{{ $venta->cliente ?: 'Público General' }}</span>
        </div>

        <div class="border-t border-dashed border-black my-2"></div>

        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left w-[15%] pb-1">Cant</th>
                    <th class="text-left w-[50%] pb-1">Descripción</th>
                    <th class="text-right w-[35%] pb-1">Importe</th>
                </tr>
            </thead>
            <tbody>
                @php $totalDescuento = 0; @endphp
                @foreach($venta->detalles as $detalle)
                    @php $totalDescuento += $detalle->descuento; @endphp
                    <tr>
                        <td class="text-left align-top py-0.5">{{ $detalle->cantidad }}</td>
                        <td class="text-left align-top py-0.5 pr-1">{{ $detalle->nombre_producto }}</td>
                        <td class="text-right align-top py-0.5">${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                    
                    @if($detalle->descuento > 0)
                    <tr>
                        <td></td>
                        <td class="text-left text-[10px] italic py-0.5">Ahorro aplicado:</td>
                        <td class="text-right text-[10px] italic py-0.5">-${{ number_format($detalle->descuento, 2) }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="border-t border-dashed border-black my-2"></div>

        <div class="flex flex-col items-end w-full">
            @if($totalDescuento > 0)
            <div class="flex justify-between w-[75%]">
                <span class="font-bold">Subtotal:</span>
                <span>${{ number_format($venta->total + $totalDescuento, 2) }}</span>
            </div>
            <div class="flex justify-between w-[75%]">
                <span class="font-bold">Ahorro Total:</span>
                <span>-${{ number_format($totalDescuento, 2) }}</span>
            </div>
            @endif
            
            <div class="flex justify-between w-[75%] font-bold text-[15px] pt-1.5">
                <span>TOTAL:</span>
                <span>${{ number_format($venta->total, 2) }}</span>
            </div>
            <div class="flex justify-between w-[75%] pt-1.5">
                <span>Pago con:</span>
                <span>${{ number_format($venta->pago_con, 2) }}</span>
            </div>
            <div class="flex justify-between w-[75%]">
                <span>Cambio:</span>
                <span>${{ number_format($venta->cambio, 2) }}</span>
            </div>
        </div>

        <div class="border-t border-dashed border-black my-2"></div>

        <div class="text-center mt-4">
            ¡Gracias por su preferencia!<br>
            Vuelva pronto.
        </div>

        @if($venta->requiere_factura)
        <div class="text-center mt-4 font-bold border-2 border-black p-1 uppercase tracking-widest">
            * REQUIERE FACTURA *
        </div>
        @endif
        
        <div class="text-center mt-3 text-[10px] text-gray-500 print:text-black">
            Generado por Llantas Económicas POS
        </div>

    </div>

    <script>
        window.onload = function() {
            window.print();
        }

        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>
</html>