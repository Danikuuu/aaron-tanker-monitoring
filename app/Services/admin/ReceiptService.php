<?php

namespace App\Services\Admin;

use App\Repositories\Admin\ReceiptInterface;
use App\Models\TankerDeparture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReceiptService
{
    public function __construct(
        protected ReceiptInterface $receiptRepository
    ) {}

    public function getReceiptData(int $id): array
    {
        $departure  = $this->receiptRepository->findDeparture($id);
        $grandTotal = $departure->fuels->sum(fn($f) => $f->liters * ($f->unit_price ?? 0));

        return [
            'departure'  => $departure,
            'grandTotal' => $grandTotal,
        ];
    }

    public function downloadPdf(int $id): Response
    {
        $data = $this->getReceiptData($id);

        $pdf = Pdf::loadView('admin.receipt-pdf', $data)
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'defaultFont'     => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'dpi'             => 150,
            ]);

        $filename = 'receipt-' . $data['departure']->tanker_number . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}