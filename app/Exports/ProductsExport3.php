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

class ProductsExport3 implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, WithEvents
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
                'O',
            ],
            // Baris kedua header sub
            [
                'ID',
                'Nama Produk',
                'Kode Item',
                'Stok',
                'Harga Pokok',
                'Harga Grosir',
                'Harga Jual',
                'Supplier',
                'Brand',
                'Kategori',
                'Koperasi',
                'Tanggal Dibuat',
                'Tanggal Diperbarui',
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
            $product->id,
            $product->product_name,
            $product->item_code,
            $product->stock,
            str_replace(['Rp', '.', ','], '', (string) $product->cost_price),          // Harga Pokok tanpa simbol dan titik
            str_replace(['Rp', '.', ','], '', (string) $product->wholesale_price),     // Harga Grosir tanpa simbol dan titik
            str_replace(['Rp', '.', ','], '', (string) $product->sale_price),          // Harga Jual tanpa simbol dan titik
            $product->supplier->supplier_name,
            $product->brand->brand_name,
            $product->category->category_name,
            $product->coop->coop_name,
            Carbon::parse($product->created_at)->format('d/m/Y'),
            Carbon::parse($product->updated_at)->format('d/m/Y'),
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
            'A' => NumberFormat::FORMAT_TEXT,        // ID sebagai teks
            'B' => NumberFormat::FORMAT_TEXT,        // Nama Produk sebagai teks
            'C' => NumberFormat::FORMAT_TEXT,        // Kode Item sebagai teks
            'D' => NumberFormat::FORMAT_TEXT,        // Stok sebagai teks
            'E' => NumberFormat::FORMAT_TEXT,        // Harga Pokok sebagai teks
            'F' => NumberFormat::FORMAT_TEXT,        // Harga Grosir sebagai teks
            'G' => NumberFormat::FORMAT_TEXT,        // Harga Jual sebagai teks
            'H' => NumberFormat::FORMAT_TEXT,        // Supplier sebagai teks
            'I' => NumberFormat::FORMAT_TEXT,        // Brand sebagai teks
            'J' => NumberFormat::FORMAT_TEXT,        // Kategori sebagai teks
            'K' => NumberFormat::FORMAT_TEXT,        // Koperasi sebagai teks
            'L' => NumberFormat::FORMAT_TEXT,        // Tanggal Dibuat sebagai teks
            'M' => NumberFormat::FORMAT_TEXT,        // Tanggal Diperbarui sebagai teks
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

                $event->sheet->getDelegate();
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
