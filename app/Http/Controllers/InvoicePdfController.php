<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function download(Order $order)
    {
        abort_if($order->user_id !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $order->load(['user', 'items.product']);

        $pdf = Pdf::loadView('pdf.invoice', compact('order'))
            ->setPaper('a4');

        return $pdf->download($order->invoice_number . '.pdf');
    }
}