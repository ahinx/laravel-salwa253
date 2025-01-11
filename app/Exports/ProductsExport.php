<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, WithEvents
{
    use Exportable;

    protected $filters;

    /**
     * Konstruktor untuk menerima filter
     *
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Query yang akan diexport
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $query = Product::query();

        // Terapkan filter berdasarkan tanggal mulai
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }

        // Terapkan filter berdasarkan tanggal akhir
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        // Terapkan filter berdasarkan koperasi
        if (!empty($this->filters['coop_id'])) {
            $query->where('coop_id', $this->filters['coop_id']);
        }

        // Terapkan filter berdasarkan nama produk
        if (!empty($this->filters['product_name'])) {
            $query->where('product_name', 'like', '%' . $this->filters['product_name'] . '%');
        }

        // Terapkan filter berdasarkan kategori
        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        // Terapkan filter berdasarkan brand
        if (!empty($this->filters['brand_id'])) {
            $query->where('brand_id', $this->filters['brand_id']);
        }

        // Terapkan filter berdasarkan supplier
        if (!empty($this->filters['supplier_id'])) {
            $query->where('supplier_id', $this->filters['supplier_id']);
        }

        // Eager load relasi untuk menghindari N+1 problem
        $query->with(['coop', 'brand', 'category', 'supplier']);

        // Pilih kolom yang diperlukan
        return $query->select('id', 'product_name', 'item_code', 'stock', 'cost_price', 'wholesale_price', 'sale_price', 'supplier_id', 'brand_id', 'category_id', 'coop_id', 'created_at', 'updated_at');
    }

    /**
     * Headings untuk kolom Excel (dua baris)
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            // Baris pertama header utama
            [
                'Tipe',
                'L',
            ],
            // Baris kedua header sub
            [
                'KODE ITEM',
                'NAMA ITEM',
                'JENIS',
                'MEREK',
                'SATUAN 1',
                'SATUAN 2',
                'SATUAN 3',
                'SATUAN 4',
                'BARCODE SATUAN 1',
                'BARCODE SATUAN 2',
                'BARCODE SATUAN 3',
                'BARCODE SATUAN 4',
                'KONVERSI SATUAN 1',
                'KONVERSI SATUAN 2',
                'KONVERSI SATUAN 3',
                'KONVERSI SATUAN 4',
                'HARGA POKOK SATUAN 1',
                'HARGA POKOK SATUAN 2',
                'HARGA POKOK SATUAN 3',
                'HARGA POKOK SATUAN 4',
                'HARGA JUAL SATUAN 1 (LEVEL 1)',
                'HARGA JUAL SATUAN 2 (LEVEL 1)',
                'HARGA JUAL SATUAN 3 (LEVEL 1)',
                'HARGA JUAL SATUAN 4 (LEVEL 1)',
                'HARGA JUAL SATUAN 1 (LEVEL 2)',
                'HARGA JUAL SATUAN 2 (LEVEL 2)',
                'HARGA JUAL SATUAN 3 (LEVEL 2)',
                'HARGA JUAL SATUAN 4 (LEVEL 2)',
                'HARGA JUAL SATUAN 1 (LEVEL 3)',
                'HARGA JUAL SATUAN 2 (LEVEL 3)',
                'HARGA JUAL SATUAN 3 (LEVEL 3)',
                'HARGA JUAL SATUAN 4 (LEVEL 3)',
                'HARGA JUAL SATUAN 1 (LEVEL 4)',
                'HARGA JUAL SATUAN 2 (LEVEL 4)',
                'HARGA JUAL SATUAN 3 (LEVEL 4)',
                'HARGA JUAL SATUAN 4 (LEVEL 4)',
                'HARGA JUAL SATUAN 1 (LEVEL 5)',
                'HARGA JUAL SATUAN 2 (LEVEL 5)',
                'HARGA JUAL SATUAN 3 (LEVEL 5)',
                'HARGA JUAL SATUAN 4 (LEVEL 5)',
                'HARGA JUAL SATUAN 1 (LEVEL 6)',
                'HARGA JUAL SATUAN 2 (LEVEL 6)',
                'HARGA JUAL SATUAN 3 (LEVEL 6)',
                'HARGA JUAL SATUAN 4 (LEVEL 6)',
                'POIN 1',
                'POIN 2',
                'POIN 3',
                'POIN 4',
                'KOMISI SALES 1',
                'KOMISI SALES 2',
                'KOMISI SALES 3',
                'KOMISI SALES 4',
                'STOK AWAL SATUAN DASAR',
                'STOK MINIMUM',
                'TIPE ITEM',
                'MENGGUNAKAN SERIAL',
                'RAK',
                'KODE GUDANG -KANTOR',
                'KODE SUPPLIER',
                'KONSINYASI',
                'SISTEM HPP',
                'KETERANGAN',
                'PAJAK INCLUDE (%)',
            ],
        ];
    }

    /**
     * Mapping data untuk setiap baris di Excel
     *
     * @param \App\Models\Product $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->item_code,                  // KODE ITEM ( wajib Teks dengan diawali petik depan '21354121)
            $product->product_name,                   // NAMA ITEM
            $product->category->category_name,                  // JENIS
            $product->brand->brand_name,                   // MEREK
            "PTG",                  // SATUAN 1
            "",                   // SATUAN 2
            "",                   // SATUAN 3
            "",                   // SATUAN 4
            $product->item_code,                   // BARCODE SATUAN 1 ( wajib Teks dengan diawali petik depan '21354121)
            "",                   // BARCODE SATUAN 2
            "",                   // BARCODE SATUAN 3
            "",                   // BARCODE SATUAN 4
            "",                   // KONVERSI SATUAN 1
            "",                   // KONVERSI SATUAN 2
            "",                   // KONVERSI SATUAN 3
            "",                   // KONVERSI SATUAN 4
            str_replace(['Rp', '.', ','], '', (string) $product->cost_price),                   // HARGA POKOK SATUAN 1
            "",                   // HARGA POKOK SATUAN 2
            "",                   // HARGA POKOK SATUAN 3
            "",                   // HARGA POKOK SATUAN 4
            str_replace(['Rp', '.', ','], '', (string) $product->sale_price),                     // HARGA JUAL SATUAN 1 (LEVEL 1)
            "",                   // HARGA JUAL SATUAN 2 (LEVEL 1)
            "",                   // HARGA JUAL SATUAN 3 (LEVEL 1)
            "",                   // HARGA JUAL SATUAN 4 (LEVEL 1)
            str_replace(['Rp', '.', ','], '', (string) $product->wholesale_price),                  // HARGA JUAL SATUAN 1 (LEVEL 2)
            "",                   // HARGA JUAL SATUAN 2 (LEVEL 2)
            "",                   // HARGA JUAL SATUAN 3 (LEVEL 2)
            "",                   // HARGA JUAL SATUAN 4 (LEVEL 2)
            "",                   // HARGA JUAL SATUAN 1 (LEVEL 3)
            "",                   // HARGA JUAL SATUAN 2 (LEVEL 3)
            "",                   // HARGA JUAL SATUAN 3 (LEVEL 3)
            "",                   // HARGA JUAL SATUAN 4 (LEVEL 3)
            "",                   // HARGA JUAL SATUAN 1 (LEVEL 4)
            "",                   // HARGA JUAL SATUAN 2 (LEVEL 4)
            "",                   // HARGA JUAL SATUAN 3 (LEVEL 4)
            "",                   // HARGA JUAL SATUAN 4 (LEVEL 4)
            "",                   // HARGA JUAL SATUAN 1 (LEVEL 5)
            "",                   // HARGA JUAL SATUAN 2 (LEVEL 5)
            "",                   // HARGA JUAL SATUAN 3 (LEVEL 5)
            "",                   // HARGA JUAL SATUAN 4 (LEVEL 5)
            "",                   // HARGA JUAL SATUAN 1 (LEVEL 6)
            "",                   // HARGA JUAL SATUAN 2 (LEVEL 6)
            "",                   // HARGA JUAL SATUAN 3 (LEVEL 6)
            "",                   // HARGA JUAL SATUAN 4 (LEVEL 6)
            "",                   // POIN 1
            "",                   // POIN 2
            "",                   // POIN 3
            "",                   // POIN 4
            "",                   // KOMISI SALES 1
            "",                   // KOMISI SALES 2
            "",                   // KOMISI SALES 3
            "",                   // KOMISI SALES 4
            "",                   // STOK AWAL SATUAN DASAR
            "",                   // STOK MINIMUM
            "1",                   // TIPE ITEM
            "N",                   // MENGGUNAKAN SERIAL
            "",                  // RAK
            "UTM",                   // KODE GUDANG -KANTOR
            $product->supplier->supplier_name,                   // KODE SUPPLIER
            "Y",                   // KONSINYASI
            "FIFO",                   // SISTEM HPP
            "",                   // KETERANGAN
            "",                   // PAJAK INCLUDE (%)
        ];
    }

    /**
     * Mengatur format kolom tertentu
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => '@',        // 
            'B' => '@',        // 
            'C' => '@',        // 
            'D' => '@',        //
            'E' => '@',        // 
            'F' => '@',        // 
            'G' => '@',        // 
            'H' => '@',        // 
            'I' => '@',        // 
            'J' => '@',        // 
            'K' => '@',        // 
            'L' => '@',        // 
            'M' => '@',        // 
            'N' => '@',        //
            'O' => '@',        //
            'P' => '@',        //
            'Q' => '@',        //
            'R' => '@',        //
            'S' => '@',        //
            'T' => '@',        //
            'U' => '@',        //
            'V' => '@',        //
            'W' => '@',        //
            'X' => '@',        //
            'Y' => '@',        //
            'Z' => '@',        //
            'AA' => '@',        //
            'AB' => '@',        //
            'AC' => '@',        //
            'AD' => '@',        //
            'AE' => '@',        //
            'AF' => '@',        //
            'AG' => '@',        //
            'AH' => '@',        //
            'AI' => '@',        //
            'AJ' => '@',        //
            'AK' => '@',        //
            'AL' => '@',        //
            'AM' => '@',        //
            'AN' => '@',        //
            'AO' => '@',        //
            'AP' => '@',        //
            'AQ' => '@',        //
            'AR' => '@',        //
            'AS' => '@',        //
            'AT' => '@',        //
            'AU' => '@',        //
            'AV' => '@',        //
            'AW' => '@',        //
            'AX' => '@',        //
            'AY' => '@',        //
            'AZ' => '@',        //
            'BA' => '@',        //
            'BB' => '@',        //
            'BC' =>  NumberFormat::FORMAT_NUMBER,    //
            'BD' => '@',        //
            'BE' => '@',        //
            'BF' => '@',        //
            'BG' => '@',        //
            'BH' => '@',        //
            'BI' => '@',        //
            'BJ' => '@',        //
            'BK' => '@',        //


            // 'A' => NumberFormat::FORMAT_TEXT,        // 
            // 'B' => NumberFormat::FORMAT_TEXT,        // 
            // 'C' => NumberFormat::FORMAT_TEXT,        // 
            // 'D' => NumberFormat::FORMAT_TEXT,        //
            // 'E' => NumberFormat::FORMAT_TEXT,        // 
            // 'F' => NumberFormat::FORMAT_TEXT,        // 
            // 'G' => NumberFormat::FORMAT_TEXT,        // 
            // 'H' => NumberFormat::FORMAT_TEXT,        // 
            // 'I' => NumberFormat::FORMAT_TEXT,        // 
            // 'J' => NumberFormat::FORMAT_TEXT,        // 
            // 'K' => NumberFormat::FORMAT_TEXT,        // 
            // 'L' => NumberFormat::FORMAT_TEXT,        // 
            // 'M' => NumberFormat::FORMAT_TEXT,        // 
            // 'N' => NumberFormat::FORMAT_TEXT,        //
            // 'O' => NumberFormat::FORMAT_TEXT,        //
            // 'P' => NumberFormat::FORMAT_TEXT,        //
            // 'Q' => NumberFormat::FORMAT_TEXT,        //
            // 'R' => NumberFormat::FORMAT_TEXT,        //
            // 'S' => NumberFormat::FORMAT_TEXT,        //
            // 'T' => NumberFormat::FORMAT_TEXT,        //
            // 'U' => NumberFormat::FORMAT_TEXT,        //
            // 'V' => NumberFormat::FORMAT_TEXT,        //
            // 'W' => NumberFormat::FORMAT_TEXT,        //
            // 'X' => NumberFormat::FORMAT_TEXT,        //
            // 'Y' => NumberFormat::FORMAT_TEXT,        //
            // 'Z' => NumberFormat::FORMAT_TEXT,        //
            // 'AA' => NumberFormat::FORMAT_TEXT,        //
            // 'AB' => NumberFormat::FORMAT_TEXT,        //
            // 'AC' => NumberFormat::FORMAT_TEXT,        //
            // 'AD' => NumberFormat::FORMAT_TEXT,        //
            // 'AE' => NumberFormat::FORMAT_TEXT,        //
            // 'AF' => NumberFormat::FORMAT_TEXT,        //
            // 'AG' => NumberFormat::FORMAT_TEXT,        //
            // 'AH' => NumberFormat::FORMAT_TEXT,        //
            // 'AI' => NumberFormat::FORMAT_TEXT,        //
            // 'AJ' => NumberFormat::FORMAT_TEXT,        //
            // 'AK' => NumberFormat::FORMAT_TEXT,        //
            // 'AL' => NumberFormat::FORMAT_TEXT,        //
            // 'AM' => NumberFormat::FORMAT_TEXT,        //
            // 'AN' => NumberFormat::FORMAT_TEXT,        //
            // 'AO' => NumberFormat::FORMAT_TEXT,        //
            // 'AP' => NumberFormat::FORMAT_TEXT,        //
            // 'AQ' => NumberFormat::FORMAT_TEXT,        //
            // 'AR' => NumberFormat::FORMAT_TEXT,        //
            // 'AS' => NumberFormat::FORMAT_TEXT,        //
            // 'AT' => NumberFormat::FORMAT_TEXT,        //
            // 'AU' => NumberFormat::FORMAT_TEXT,        //
            // 'AV' => NumberFormat::FORMAT_TEXT,        //
            // 'AW' => NumberFormat::FORMAT_TEXT,        //
            // 'AX' => NumberFormat::FORMAT_TEXT,        //
            // 'AY' => NumberFormat::FORMAT_TEXT,        //
            // 'AZ' => NumberFormat::FORMAT_TEXT,        //
            // 'BA' => NumberFormat::FORMAT_TEXT,        //
            // 'BB' => NumberFormat::FORMAT_TEXT,        //
            // 'BC' =>  NumberFormat::FORMAT_NUMBER,    //
            // 'BD' => NumberFormat::FORMAT_TEXT,        //
            // 'BE' => NumberFormat::FORMAT_TEXT,        //
            // 'BF' => NumberFormat::FORMAT_TEXT,        //
            // 'BG' => NumberFormat::FORMAT_TEXT,        //
            // 'BH' => NumberFormat::FORMAT_TEXT,        //
            // 'BI' => NumberFormat::FORMAT_TEXT,        //
            // 'BJ' => NumberFormat::FORMAT_TEXT,        //
            // 'BK' => NumberFormat::FORMAT_TEXT,        //




            // //contoh return format
            // 'B' => NumberFormat::FORMAT_TEXT, // Nama Produk sebagai teks
            // 'C' => NumberFormat::FORMAT_NUMBER_00, // Kode Item dengan dua desimal
            // 'D' => NumberFormat::FORMAT_NUMBER,     // Stok tanpa desimal
            // 'E' => NumberFormat::FORMAT_NUMBER_00, // Harga Pokok dengan dua desimal
            // 'F' => '0.00%',                        // Harga Grosir sebagai persentase
            // 'G' => '"Rp" #,##0.00',                // Harga Jual dalam Rupiah dengan dua desimal
            // 'L' => 'dd/mm/yyyy',                   // Tanggal Dibuat
            // 'M' => 'dd/mm/yyyy',                   // Tanggal Diperbarui

        ];
    }

    /**
     * Menambahkan styling pada worksheet
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Styling baris header pertama menjadi bold dan center alignment
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Styling baris header kedua menjadi bold
            2 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Menangani event untuk AfterSheet
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:Z1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                // $event->sheet->getDelegate();
                // $sheet = $event->sheet->getDelegate();

                // // Menggabungkan sel untuk header pertama
                // $sheet->mergeCells('A1:D1'); // Menggabungkan kolom A hingga D untuk "Product Details"
                // $sheet->mergeCells('E1:G1'); // Menggabungkan kolom E hingga G untuk "Pricing Information"
                // $sheet->mergeCells('H1:J1'); // Menggabungkan kolom H hingga J untuk "Supplier & Brand"
                // $sheet->mergeCells('K1:K1'); // Kolom K untuk "Organization" (tidak perlu merge)
                // $sheet->mergeCells('L1:M1'); // Menggabungkan kolom L hingga M untuk "Tanggal"

                // // Menambahkan border ke seluruh header
                // $sheet->getStyle('A1:M2')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // // Mengatur text wrap agar teks panjang tidak terpotong
                // $sheet->getStyle('A1:M2')->getAlignment()->setWrapText(true);

                // // Menambahkan warna latar belakang pada header pertama
                // $sheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //     ->getStartColor()->setARGB('FFD9D9D9'); // Warna abu-abu terang

                // // Menambahkan warna latar belakang pada header kedua
                // $sheet->getStyle('A2:M2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //     ->getStartColor()->setARGB('FFBFBFBF'); // Warna abu-abu sedikit gelap
            },
        ];
    }
}
