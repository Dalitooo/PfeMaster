<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    @vite(['resources/css/app.css'])
    <style>@media print { body { print-color-adjust: exact; } }</style>
</head>
<body class="bg-white p-8 max-w-2xl mx-auto font-sans text-slate-800">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold text-blue-600">SmileCare Cabinet Dentaire</h1>
            <p class="text-slate-500 text-sm">Soins dentaires professionnels</p>
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold">{{ $invoice->invoice_number }}</p>
            <p class="text-sm text-slate-500 mt-0.5">{{ $invoice->created_at->format('M j, Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-8 text-sm">
        <div>
            <p class="font-semibold text-slate-400 text-xs uppercase mb-1">Facturé à</p>
            <p class="font-bold">{{ $invoice->patient->name }}</p>
            <p class="text-slate-500">{{ $invoice->patient->email }}</p>
            @if($invoice->patient->phone)<p class="text-slate-500">{{ $invoice->patient->phone }}</p>@endif
        </div>
        <div class="text-right text-slate-600">
            @if($invoice->due_date)<p><span class="text-slate-400">Échéance :</span> {{ $invoice->due_date->format('M j, Y') }}</p>@endif
            <p><span class="text-slate-400">Statut :</span> <span class="font-bold {{ $invoice->status === 'paid' ? 'text-green-600' : 'text-red-600' }}">{{ strtoupper($invoice->status) }}</span></p>
        </div>
    </div>

    <table class="w-full text-sm border-collapse mb-6">
        <thead>
            <tr class="bg-slate-100">
                <th class="text-left p-2.5 font-semibold">Description</th>
                <th class="text-right p-2.5 font-semibold">Qté</th>
                <th class="text-right p-2.5 font-semibold">Prix</th>
                <th class="text-right p-2.5 font-semibold">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr class="border-b border-slate-100">
                    <td class="p-2.5">{{ $item->description }}</td>
                    <td class="p-2.5 text-right">{{ $item->quantity }}</td>
                    <td class="p-2.5 text-right">DT {{ number_format($item->unit_price, 2) }}</td>
                    <td class="p-2.5 text-right font-medium">DT {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-end mb-8">
        <div class="w-48 text-sm space-y-1">
            <div class="flex justify-between"><span class="text-slate-500">Sous-total</span><span>DT {{ number_format($invoice->subtotal, 2) }}</span></div>
            @if($invoice->discount > 0)<div class="flex justify-between text-green-600"><span>Remise</span><span>−DT {{ number_format($invoice->discount, 2) }}</span></div>@endif
            @if($invoice->tax > 0)<div class="flex justify-between"><span>Taxe</span><span>DT {{ number_format($invoice->tax, 2) }}</span></div>@endif
            <div class="flex justify-between font-bold text-base border-t pt-1.5 mt-1"><span>Total</span><span>DT {{ number_format($invoice->total, 2) }}</span></div>
        </div>
    </div>

    <div class="text-center text-xs text-slate-400 border-t pt-4">Merci de votre confiance envers SmileCare Cabinet Dentaire</div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
