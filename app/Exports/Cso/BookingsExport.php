<?php

namespace App\Exports\Cso;

use App\Models\Cso\Booking;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $title;
    protected $period;

    // Properties to hold totals
    protected $totalHargaStd = 0;
    protected $totalBuses = 0;
    protected $totalDiskon = 0;
    protected $totalBiayaJemput = 0;
    protected $totalGrandTotal = 0;

    public function __construct($startDate, $endDate, $title = 'Bookings Report', $period = '')
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->title = $title;
        $this->period = $period;
    }

    public function collection()
    {
        $bookings = Booking::with(['bookingDetails'])
            ->whereBetween('date_start', [$this->startDate, $this->endDate])
            ->get();

        Carbon::setLocale('id');

        foreach ($bookings as $booking) {
            $startDate = Carbon::parse($booking->date_start);
            $endDate = Carbon::parse($booking->date_end);
            $duration_days = $startDate->diffInDays($endDate) + 1;

            if ($startDate->isSameDay($endDate)) {
                $booking->formatted_date_range = $startDate->isoFormat('dddd, DD-MM-YYYY');
            } else {
                $booking->formatted_date_range = $startDate->isoFormat('dddd, DD-MM-YYYY') . " s/d " . $endDate->isoFormat('dddd, DD-MM-YYYY');
            }

            $booking->duration_days = $duration_days;

            // Accumulate totals
            $this->totalHargaStd += $booking->harga_std;
            $this->totalBuses += $booking->bookingDetails->count();
            $this->totalDiskon += $booking->diskon ?? 0;
            $this->totalBiayaJemput += $booking->biaya_jemput ?? 0;
            $this->totalGrandTotal += $booking->grand_total;
        }

        return $bookings;
    }

    public function headings(): array
    {
        return [
            'No Booking',
            'Nama Customer',
            'Telephone',
            'Tanggal Pemakaian',
            'Durasi',
            'Tujuan',
            'Harga Std',
            'Total Bus',
            'Diskon',
            'Biaya Jemput',
            'Grand Total'
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->no_booking,
            $booking->customer,
            $booking->telephone,
            $booking->formatted_date_range,
            $booking->duration_days . ' Hari',
            $booking->tujuan ? $booking->tujuan->nama_tujuan : 'Tidak tersedia',
            $booking->harga_std,
            $booking->bookingDetails->count(),
            $booking->diskon ?? 0,
            $booking->biaya_jemput ?? 0,
            $booking->grand_total
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styles for headers
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF00']],
        ]);

        // Styles for all cells
        $sheet->getStyle('A1:K' . $sheet->getHighestRow())->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->prependRows(2, [
                    [$this->title],
                    [$this->period],
                    [], // Empty row for spacing
                ]);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Define styles for total row
                $styleArray = [
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFA0A0A0']],
                ];

                // Add total row
                // $sheet->setCellValue('F' . ($highestRow + 1), 'Total');
                // $sheet->getStyle('F' . ($highestRow + 1))->applyFromArray($styleArray);
                // $sheet->setCellValue('G' . ($highestRow + 1), $this->totalHargaStd);
                // $sheet->getStyle('G' . ($highestRow + 1))->applyFromArray($styleArray);
                // $sheet->setCellValue('H' . ($highestRow + 1), $this->totalBuses);
                // $sheet->getStyle('H' . ($highestRow + 1))->applyFromArray($styleArray);
                // $sheet->setCellValue('I' . ($highestRow + 1), $this->totalDiskon);
                // $sheet->getStyle('I' . ($highestRow + 1))->applyFromArray($styleArray);
                // $sheet->setCellValue('J' . ($highestRow + 1), $this->totalBiayaJemput);
                // $sheet->getStyle('J' . ($highestRow + 1))->applyFromArray($styleArray);
                // $sheet->setCellValue('K' . ($highestRow + 1), $this->totalGrandTotal);
                // $sheet->getStyle('K' . ($highestRow + 1))->applyFromArray($styleArray);

                // // Set borders for total row
                // $sheet->getStyle('F' . ($highestRow + 1) . ':K' . ($highestRow + 1))->applyFromArray([
                //     'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                // ]);
            },
        ];
    }
}
