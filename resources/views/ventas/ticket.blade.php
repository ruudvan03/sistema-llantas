<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket {{ $venta->folio }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .ticket-wrapper { box-shadow: none !important; margin: 0 !important; }
        }
    </style>
</head>
<body class="bg-gray-200 flex justify-center print:bg-white font-mono text-black min-h-screen py-6 print:py-0">

    <div class="ticket-wrapper w-[80mm] bg-white p-4 text-[12px] shadow-xl mt-2 print:w-full print:p-0 print:shadow-none print:mt-0 print:border-none rounded-lg">

        {{-- ── Botón imprimir ── --}}
        <button onclick="window.print()" class="no-print w-full py-3 mb-4 bg-emerald-500 text-white font-bold rounded-lg hover:bg-emerald-600 active:scale-95 transition text-sm">
            🖨️ Imprimir Ticket
        </button>

        {{-- ── Encabezado ── --}}
        <div class="text-center font-bold text-[15px] mb-0.5 tracking-widest">
            🛞 LLANTAS ECONÓMICAS 🛞
        </div>
        <div class="text-center text-[10px] mb-1 leading-tight text-gray-600 print:text-black">
            🏁 Sistema de Punto de Venta 🏁<br>
            Sucursal: {{ $venta->sucursal_id }}
        </div>

        <div class="border-t-2 border-dashed border-black my-2"></div>

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

        <div class="border-t-2 border-dashed border-black my-2"></div>

        <table class="w-full">
            <thead>
                <tr class="border-b border-dashed border-gray-400">
                    <th class="text-left w-[12%] pb-1">Cant</th>
                    <th class="text-left w-[53%] pb-1">Descripción</th>
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
                        <td class="text-left text-[10px] italic py-0.5">🏷️ Ahorro:</td>
                        <td class="text-right text-[10px] italic py-0.5">-${{ number_format($detalle->descuento, 2) }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="border-t-2 border-dashed border-black my-2"></div>

        <div class="flex flex-col items-end w-full">
            @if($totalDescuento > 0)
            <div class="flex justify-between w-[75%]">
                <span class="font-bold">Subtotal:</span>
                <span>${{ number_format($venta->total + $totalDescuento, 2) }}</span>
            </div>
            <div class="flex justify-between w-[75%]">
                <span class="font-bold">🏷️ Ahorro Total:</span>
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

        <div class="border-t-2 border-dashed border-black my-2"></div>

        {{-- ════════ BLOQUE QR ════════ --}}
        @if($venta->requiere_factura)

            {{-- Un solo QR que funciona en pantalla e impresión --}}
            <div class="mt-2 mb-3 border-2 border-dashed border-black rounded-xl p-3 text-center">
                <p class="font-black text-[13px] mb-1">🧾🛞 ¿VAS A FACTURAR? 🛞🧾</p>
                <p class="text-[10px] mb-3 leading-tight">
                    Escanea el QR para enviarnos<br>tus datos fiscales por WhatsApp 🚗💨
                </p>

                {{-- Fondo blanco hardcodeado: las variables CSS pueden ir a gris en modo oscuro --}}
                <div class="flex justify-center mb-1">
                    <div id="qr-factura"
                         style="background:#ffffff; padding:8px; display:inline-block; line-height:0; border-radius:6px;">
                    </div>
                </div>
            </div>

        @else

            <div class="no-print border border-dashed border-gray-300 rounded-xl p-3 text-center mb-3">
                <p class="text-[11px] text-gray-400 font-bold">🛞 Sin solicitud de factura</p>
                <p class="text-[9px] text-gray-400 mt-1">
                    Marca "Requiere Factura" en la venta<br>para que aparezca el QR aquí.
                </p>
            </div>

        @endif

        {{-- ── Pie ── --}}
        <div class="text-center mt-3 font-bold text-[13px]">
            🏁 ¡Gracias por su preferencia! 🏁<br>
            <span class="text-[11px] font-normal">¡Vuelva pronto, lo esperamos! 🛞🚗</span>
        </div>
        <div class="text-center mt-3 text-[9px] text-gray-400 print:text-black">
            Generado por Llantas Económicas POS
        </div>

    </div>

    {{-- ════════ SCRIPT: genera el QR con la URL de WhatsApp ════════ --}}
    <script>
    (function () {
        var telefono = "525535690077";
        var folio    = @json($venta->folio);
        var total    = @json(number_format($venta->total, 2));
        var cliente  = @json($venta->cliente ?: 'Público General');

        var mensaje =
            "🛞 ¡Hola! Soy *" + cliente + "*, vengo de Llantas Económicas Chalco\n" +
            "y quiero facturar mi compra 🧾🔥\n\n" +
            "📋 Folio: *#" + folio + "*\n" +
            "💰 Total: *$" + total + "*\n\n" +
            "Estos son mis datos fiscales 👇\n" +
            "RFC:\n" +
            "Razón social:\n" +
            "Uso de CFDI:\n" +
            "Código postal:\n\n" +
            "¡Gracias, quedo al pendiente! 🚗💨";

        var url = "https://wa.me/" + telefono + "?text=" + encodeURIComponent(mensaje);

        var el = document.getElementById("qr-factura");
        if (el && typeof QRCode !== "undefined") {
            new QRCode(el, {
                text: url,
                width: 180,
                height: 180,
                colorDark:  "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }
    })();
    </script>
</body>
</html>