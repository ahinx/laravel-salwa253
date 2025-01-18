<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Coop;
use App\Models\Product;
use App\Models\Size;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Jika ada pencarian
        if ($search) {
            // Ambil produk berdasarkan pencarian tanpa pagination
            $products = Product::with(['colors', 'sizes', 'supplier', 'coop', 'category', 'brand', 'photos'])
                ->where('product_name', 'like', "%{$search}%")
                ->orWhere('item_code', 'like', "%{$search}%")
                ->orWhereHas('coop', function ($query) use ($search) {
                    $query->where('coop_name', 'like', "%{$search}%");
                })
                ->orWhereHas('supplier', function ($query) use ($search) {
                    $query->where('supplier_name', 'like', "%{$search}%");
                })
                ->orWhereHas('brand', function ($query) use ($search) {
                    $query->where('brand_name', 'like', "%{$search}%");
                })
                ->orWhereHas('category', function ($query) use ($search) {
                    $query->where('category_name', 'like', "%{$search}%");
                })
                ->get();  // Menggunakan get() untuk mengambil hasil tanpa pagination

            // Menghitung jumlah produk yang ditemukan
            $total = $products->count();
        } else {
            // Jika tidak ada pencarian, menggunakan pagination
            $products = Product::with(['colors', 'sizes', 'supplier', 'coop', 'category', 'brand', 'photos'])->orderBy('created_at', 'desc')->paginate(10);
            $total = null;  // Tidak menampilkan jumlah hasil pencarian
        }

        // Jika request adalah AJAX, return partial view
        if ($request->ajax()) {
            return view('products.partials.product_list', compact('products'))->render();
        }

        // Return view lengkap
        return view('products.index', compact('products', 'total'));
    }




    /**
     * Show the form for creating a new resource.
     */
    // Menampilkan form untuk membuat produk baru
    public function create()
    {
        $suppliers = Supplier::all();
        $colors = Color::all();
        $sizes = Size::all();
        $coops = Coop::all();
        $categories = Category::all();
        $coops = Coop::all();
        $brands = Brand::all();

        return view('products.create', compact('suppliers', 'colors', 'sizes', 'coops', 'categories', 'brands'));
    }


    public function store1(Request $request)
    {
        // Log data request
        Log::info('Request Data:', $request->all());

        // Pastikan method ini selalu return JSON
        // TIPS: Biasakan bungkus di try-catch untuk handle error tak terduga
        try {
            // Validasi
            $request->validate([
                'product_name'      => 'required|string|max:255',
                'cost_price'        => 'required|numeric',
                'wholesale_price'   => 'required|numeric',
                'sale_price'        => 'required|numeric',
                'supplier_id'       => 'required|exists:suppliers,id',
                'colors'            => 'array',
                'sizes'             => 'array',
                'photos'            => 'array',
                'photos.*'          => 'image|mimes:jpeg,png,jpg,gif,svg|max:10120',
                'stock'             => 'required|integer|min:0',
                'brand_id'          => 'required|exists:brands,id',
                'category_id'       => 'required|exists:categories,id',
                'coop_id'           => 'required|exists:coops,id',
            ]);

            // Cek setting prefix
            $startCode = DB::table('settings')->where('key', 'item_code_start')->value('value');
            if (is_null($startCode) || trim($startCode) === '') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Isi dulu awalan kode item pada menu setting.',
                ], 422); // 422: Unprocessable Entity
            }

            // Gunakan DB Transaction agar kalau ada error, seluruh proses di-rollback
            $product = DB::transaction(function () use ($request, $startCode) {

                // Contoh: Panjang digit prefix
                $codeLength = strlen($startCode);

                // Lock table atau minimal lock row terakhir agar terhindar dari race condition
                // (opsional, tergantung skenario concurrency di project Anda)
                $lastProduct = Product::latest('item_code')
                    ->lockForUpdate()  // <-- "SELECT FOR UPDATE"
                    ->first();

                if ($lastProduct) {
                    // Ambil angka terakhir dari item_code
                    $lastCode = (int) substr($lastProduct->item_code, -$codeLength);
                    // Tambah 1
                    $newNumber = $lastCode + 1;
                    // Generate item_code baru
                    $itemCode = str_pad($newNumber, $codeLength, '0', STR_PAD_LEFT);
                } else {
                    // Jika belum ada produk
                    $itemCode = str_pad($startCode, $codeLength, '0', STR_PAD_LEFT);
                }

                // Buat instance product
                $product = Product::create([
                    'product_name'      => $request->product_name,
                    'cost_price'        => $request->cost_price,
                    'wholesale_price'   => $request->wholesale_price,
                    'sale_price'        => $request->sale_price,
                    'supplier_id'       => $request->supplier_id,
                    'item_code'         => $itemCode,
                    'stock'             => $request->stock,
                    'brand_id'          => $request->brand_id,
                    'category_id'       => $request->category_id,
                    'coop_id'           => $request->coop_id,
                ]);

                // Relasi many-to-many
                if ($request->colors) {
                    $product->colors()->sync($request->colors);
                }
                if ($request->sizes) {
                    $product->sizes()->sync($request->sizes);
                }

                // Upload foto (jika ada)
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $file) {
                        $path = $file->store('product_photos', 'public');
                        // Simpan ke tabel product_photos (jika Anda punya model relasi)
                        $product->photos()->create([
                            'photo_path' => $path
                        ]);
                    }
                }

                return $product;
            }); // end transaction

            Log::info('Product Created:', ['Product ID' => $product->id]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Product created successfully',
                'data'    => $product
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, kembalikan JSON 422
            $errors = $e->errors();
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation error',
                'errors'  => $errors
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());

            // Jika error selain validasi
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // Log data request untuk debugging
        Log::info('Request Data:', $request->all());

        try {

            Log::info('Validating request data.');

            // Validasi input
            $request->validate([
                'product_name'    => 'required|string|max:255',
                'cost_price'      => 'required|numeric',
                'wholesale_price' => 'required|numeric',
                'sale_price'      => 'required|numeric',
                'supplier_id'     => 'required|exists:suppliers,id',
                'colors'          => 'array',
                'sizes'           => 'array',
                'photos'          => 'array',
                'photos.*'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Max 10MB
                'stock'           => 'required|integer|min:0',
                'brand_id'        => 'required|exists:brands,id',
                'category_id'     => 'required|exists:categories,id',
                'coop_id'         => 'required|exists:coops,id',
            ]);



            // Ambil kode awal dari tabel settings
            Log::info('Checking item_code_start setting.');
            $startCode = DB::table('settings')->where('key', 'item_code_start')->value('value');

            if (is_null($startCode) || trim($startCode) === '') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Isi dulu awalan kode item pada menu setting.',
                ], 422); // HTTP 422: Unprocessable Entity
            }

            // Gunakan DB Transaction agar proses atomic
            Log::info('Starting DB transaction.');
            $product = DB::transaction(function () use ($request, $startCode) {
                // Dapatkan panjang digit dari kode awal
                Log::info('Generating item_code.');
                $codeLength = strlen($startCode);

                // Ambil produk terakhir dengan lock untuk mencegah race condition
                $lastProduct = Product::latest('item_code')->lockForUpdate()->first();

                if ($lastProduct) {
                    // Ambil angka terakhir dari item_code
                    $lastCode = (int) substr($lastProduct->item_code, -$codeLength);
                    // Tambah 1
                    $newNumber = $lastCode + 1;
                    // Generate item_code baru
                    $itemCode = str_pad($newNumber, $codeLength, '0', STR_PAD_LEFT);
                } else {
                    // Jika belum ada produk, gunakan kode awal dari settings
                    $itemCode = str_pad($startCode, $codeLength, '0', STR_PAD_LEFT);
                }

                // Buat instance product
                $product = Product::create([
                    'product_name'    => $request->product_name,
                    'cost_price'      => $request->cost_price,
                    'wholesale_price' => $request->wholesale_price,
                    'sale_price'      => $request->sale_price,
                    'supplier_id'     => $request->supplier_id,
                    'item_code'       => $itemCode,
                    'stock'           => $request->stock,
                    'brand_id'        => $request->brand_id,
                    'category_id'     => $request->category_id,
                    'coop_id'         => $request->coop_id,
                ]);

                // Relasi many-to-many
                if ($request->colors) {
                    $product->colors()->sync($request->colors);
                }
                if ($request->sizes) {
                    $product->sizes()->sync($request->sizes);
                }



                // Upload foto (jika ada) dan simpan ke relasi
                Log::info('Uploading photos.');

                if ($request->hasFile('photos')) {
                    Log::info('Uploading photos.');
                    foreach ($request->file('photos') as $file) {
                        $path = $file->store('product_photos', 'public');
                        if (!$path) {
                            Log::error('Failed to upload photo.');
                            throw new \Exception('Failed to upload photo.');
                        }
                        Log::info('Uploaded photo to: ' . $path);
                        // Simpan ke tabel product_photos
                        $photo = $product->photos()->create(['photo_path' => $path]);
                        if (!$photo) {
                            Log::error('Failed to save photo to database.');
                            throw new \Exception('Failed to save photo to database.');
                        }
                        Log::info('Saved photo to database: ' . $path);
                    }
                } else {
                    Log::info('No photos to upload.');
                }

                return $product;
            });

            Log::info('Product Created:', ['Product ID' => $product->id]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Product created successfully',
                'data'    => $product
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, kembalikan JSON 422
            $errors = $e->errors();
            Log::warning('Validation error: ', $errors);
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation error',
                'errors'  => $errors
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());

            // Jika error selain validasi
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Cari produk berdasarkan ID
        $product = Product::with(['photos', 'sizes', 'colors', 'coop', 'supplier'])->findOrFail($id);

        // Kembalikan tampilan dengan data produk
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // 1. Temukan product
        $product = Product::with(['colors', 'sizes', 'photos'])->findOrFail($id);

        // dd($product);

        // 2. Dapatkan master data color, size, supplier jika perlu
        $colors = Color::all();
        $sizes  = Size::all();
        $suppliers = Supplier::all();
        $coops = Coop::all();
        $categories = Category::all();
        $coops = Coop::all();
        $brands = Brand::all();

        // 3. Tampilkan view edit
        return view('products.edit', compact('product', 'colors', 'sizes', 'suppliers', 'coops', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update1(Request $request, string $id)
    {
        Log::info('Update Product Request:', $request->all());

        // Validasi...
        $request->validate([
            'product_name'    => 'required|string|max:255',
            'cost_price'      => 'required|numeric',
            'wholesale_price' => 'required|numeric',
            'sale_price'      => 'required|numeric',
            'supplier_id'     => 'required|exists:suppliers,id',
            'colors'          => 'array',
            'sizes'           => 'array',
            'photos'          => 'array',
            'photos.*'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Tambahkan 'removed_photos' => 'array' jika mau dicek validasinya
            'stock'             => 'required|integer|min:0',
            'brand_id'          => 'required|exists:brands,id',
            'category_id'       => 'required|exists:categories,id',
            'coop_id'           => 'required|exists:coops,id',
        ]);


        // 1. Dapatkan product (beserta relasi jika perlu)
        $product = Product::with(['colors', 'sizes', 'photos'])->findOrFail($id);

        // 2. Update field (TANPA mengubah item_code)
        //    Hanya update kolom yang mau di-update
        $product->update([
            'product_name'    => $request->product_name,
            'cost_price'      => $request->cost_price,
            'wholesale_price' => $request->wholesale_price,
            'sale_price'      => $request->sale_price,
            'supplier_id'     => $request->supplier_id,
            'stock'             => $request->stock,
            'brand_id'          => $request->brand_id,
            'category_id'       => $request->category_id,
            'coop_id'           => $request->coop_id,
            // 'item_code' tidak diubah
        ]);

        // // Update many-to-many warna & ukuran
        // if ($request->colors) {
        //     $product->colors()->sync($request->colors);
        // } else {
        //     $product->colors()->sync([]);
        // }
        // if ($request->sizes) {
        //     $product->sizes()->sync($request->sizes);
        // } else {
        //     $product->sizes()->sync([]);
        // }

        // Sync warna & ukuran
        $product->colors()->sync($request->colors ?: []);
        $product->sizes()->sync($request->sizes  ?: []);

        // ==== 1) Hapus foto lama yang user pilih ====
        if ($request->removed_photos) {
            // Ambil data foto lama yang ID-nya ada di removed_photos
            $photosToRemove = $product->photos()->whereIn('id', $request->removed_photos)->get();

            foreach ($photosToRemove as $p) {
                // Hapus file fisik di storage
                Storage::delete('public/' . $p->photo_path);

                // Hapus row di DB
                $p->delete();
            }
        }

        // ==== 2) Tambah foto baru (jika ada) ====
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('product_photos', 'public');
                $product->photos()->create(['photo_path' => $path]);
            }
        }

        Log::info('Product Updated:', ['Product ID' => $product->id]);

        // Return JSON
        return response()->json([
            'status'  => 'success',
            'message' => 'Product updated successfully!',
            'data'    => $product
        ]);
    }


    public function update(Request $request, string $id)
    {
        // Mulai output buffering untuk mencegah output tambahan
        ob_start();

        // Log data request untuk debugging
        Log::info('Update Product Request:', $request->all());

        try {
            Log::info('Validating request data.');

            // Validasi input
            $request->validate([
                'product_name'    => 'required|string|max:255',
                'cost_price'      => 'required|numeric',
                'wholesale_price' => 'required|numeric',
                'sale_price'      => 'required|numeric',
                'supplier_id'     => 'required|exists:suppliers,id',
                'colors'          => 'array',
                'sizes'           => 'array',
                'photos'          => 'array',
                'photos.*'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Max 10MB
                'stock'           => 'required|integer|min:0',
                'brand_id'        => 'required|exists:brands,id',
                'category_id'     => 'required|exists:categories,id',
                'coop_id'         => 'required|exists:coops,id',
                'removed_photos'  => 'array', // Jika ingin memvalidasi
                'removed_photos.*' => 'integer|exists:product_photos,id',
            ]);

            // Temukan produk
            Log::info('Finding product with ID: ' . $id);
            $product = Product::with(['colors', 'sizes', 'photos'])->findOrFail($id);

            // Update field (TANPA mengubah item_code)
            Log::info('Updating product fields.');
            $product->update([
                'product_name'    => $request->product_name,
                'cost_price'      => $request->cost_price,
                'wholesale_price' => $request->wholesale_price,
                'sale_price'      => $request->sale_price,
                'supplier_id'     => $request->supplier_id,
                'stock'           => $request->stock,
                'brand_id'        => $request->brand_id,
                'category_id'     => $request->category_id,
                'coop_id'         => $request->coop_id,
                // 'item_code' tidak diubah
            ]);

            // Sync many-to-many relationships
            Log::info('Syncing colors and sizes.');
            $product->colors()->sync($request->colors ?: []);
            $product->sizes()->sync($request->sizes ?: []);

            // Hapus foto lama yang dipilih untuk dihapus
            if ($request->removed_photos) {
                Log::info('Removing old photos.');
                $photosToRemove = $product->photos()->whereIn('id', $request->removed_photos)->get();

                foreach ($photosToRemove as $photo) {
                    // Hapus file fisik di storage
                    if (Storage::exists('public/' . $photo->photo_path)) {
                        Storage::delete('public/' . $photo->photo_path);
                        Log::info('Deleted photo file: ' . $photo->photo_path);
                    } else {
                        Log::warning('Photo file not found: ' . $photo->photo_path);
                    }

                    // Hapus record di database
                    $photo->delete();
                    Log::info('Deleted photo record with ID: ' . $photo->id);
                }
            }

            // Upload foto baru (jika ada)
            if ($request->hasFile('photos')) {
                Log::info('Uploading new photos.');
                foreach ($request->file('photos') as $file) {
                    $path = $file->store('product_photos', 'public');
                    if (!$path) {
                        Log::error('Failed to upload photo.');
                        throw new \Exception('Failed to upload photo.');
                    }
                    Log::info('Uploaded photo to: ' . $path);

                    // Simpan ke tabel product_photos
                    $photo = $product->photos()->create(['photo_path' => $path]);
                    if (!$photo) {
                        Log::error('Failed to save photo to database.');
                        throw new \Exception('Failed to save photo to database.');
                    }
                    Log::info('Saved photo to database: ' . $path);
                }
            } else {
                Log::info('No new photos to upload.');
            }

            Log::info('Product Updated:', ['Product ID' => $product->id]);

            // Bersihkan buffer
            ob_end_clean();

            // Return JSON
            return response()->json([
                'status'  => 'success',
                'message' => 'Product updated successfully!',
                'data'    => $product
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Bersihkan buffer jika ada
            if (ob_get_length()) {
                ob_end_clean();
            }

            // Jika validasi gagal, kembalikan JSON 422
            $errors = $e->errors();
            Log::warning('Validation error: ', $errors);
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation error',
                'errors'  => $errors
            ], 422);
        } catch (\Exception $e) {
            // Bersihkan buffer jika ada
            if (ob_get_length()) {
                ob_end_clean();
            }

            Log::error('Error updating product: ' . $e->getMessage());

            // Jika error selain validasi
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengupdate produk: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // 1. Temukan product dengan relasi photos (jika Anda simpan di tabel product_photos)
        $product = Product::with('photos')->findOrFail($id);

        // 2. (Opsional) Hapus file fisik foto jika disimpan di storage
        //    Loop semua photos untuk menghapus file-nya
        foreach ($product->photos as $photo) {
            // contoh: 'product_photos/abcd.jpg'
            $path = 'public/' . $photo->photo_path;  // karena disimpan di /storage/app/public/product_photos
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        // 3. Hapus data photo di DB (jika ada relasi hasMany)
        $product->photos()->delete();

        // 4. Hapus product
        $product->delete();

        // 5. Redirect / respon
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }


    public function storeCoop(Request $request)
    {
        Log::info('Masuk storeCoop, data: ', $request->all());

        $request->validate([
            'coop_name' => 'required|string|max:50',
        ]);

        $coop = Coop::create([
            'coop_name' => $request->coop_name
        ]);

        return response()->json($coop);
    }

    public function storeCategory(Request $request)
    {
        Log::info('Masuk storeCategories, data: ', $request->all());

        $request->validate([
            'category_name' => 'required|string|max:50',
        ]);

        $category = Category::create([
            'category_name' => $request->category_name
        ]);

        return response()->json($category);
    }

    public function storeBrand(Request $request)
    {
        Log::info('Masuk storeBrand, data: ', $request->all());

        $request->validate([
            'brand_name' => 'required|string|max:50',
        ]);

        $brand = Brand::create([
            'brand_name' => $request->brand_name
        ]);

        return response()->json($brand);
    }

    public function storeColor(Request $request)
    {
        Log::info('Masuk storeColor, data: ', $request->all());

        $request->validate([
            'color' => 'required|string|max:50',
        ]);

        $color = Color::create([
            'color' => $request->color
        ]);

        return response()->json($color);
    }

    public function storeSize(Request $request)
    {
        Log::info('Masuk storeSize, data: ', $request->all());

        $request->validate([
            'size' => 'required|string|max:50',
        ]);

        $size = Size::create([
            'size' => $request->size
        ]);

        return response()->json($size);
    }

    public function storeSupplier(Request $request)
    {
        Log::info('Masuk storeSupplier, data: ', $request->all());

        $request->validate([
            'supplier_name' => 'required|string|max:50',
        ]);

        $supplier = Supplier::create([
            'supplier_name' => $request->supplier_name,
            'contact' => "081"
        ]);

        return response()->json($supplier);
    }



    /**
     * Method untuk menampilkan halaman filter dan list produk
     */
    /**
     * Method untuk menampilkan halaman filter dan list produk
     */
    public function filter(Request $request)
    {
        // Log untuk memeriksa akses method
        Log::info('Filter Method Accessed', $request->all());

        // Ambil semua opsi untuk filter
        $coops = Coop::all();
        $brands = Brand::all();
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Inisialisasi query
        $query = Product::query();

        // Fungsi untuk mengubah format tanggal dari dd/mm/yyyy ke yyyy-mm-dd
        $formatTanggal = function ($tanggal) {
            if ($tanggal) {
                try {
                    return Carbon::createFromFormat('d/m/Y', $tanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Log error parsing tanggal
                    Log::error('Error parsing date: ' . $tanggal, ['error' => $e->getMessage()]);
                    // Kembalikan respon dengan error
                    abort(400, 'Format tanggal tidak valid untuk ' . $tanggal . '. Harap gunakan format dd/mm/yyyy.');
                }
            }
            return null;
        };

        // // Ambil dan ubah format filter tanggal
        // $filters = $request->only(['start_date', 'end_date', 'coop_id', 'product_name', 'category_id', 'brand_id', 'supplier_id']);
        // if (!empty($filters['start_date'])) {
        //     $filters['start_date'] = $formatTanggal($filters['start_date']);
        // }
        // if (!empty($filters['end_date'])) {
        //     $filters['end_date'] = $formatTanggal($filters['end_date']);
        // }

        // Terapkan filter berdasarkan tanggal mulai
        if ($request->filled('start_date')) {
            $start_date = $formatTanggal($request->start_date);
            if ($start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            }
        }

        // Terapkan filter berdasarkan tanggal akhir
        if ($request->filled('end_date')) {
            $end_date = $formatTanggal($request->end_date);
            if ($end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            }
        }

        // Terapkan filter berdasarkan koperasi
        if ($request->filled('coop_id')) {
            $query->where('coop_id', $request->coop_id);
        }

        // Terapkan filter berdasarkan nama produk
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        // Terapkan filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Terapkan filter berdasarkan brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Terapkan filter berdasarkan supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Eager load relasi untuk menghindari N+1 problem
        $products = $query->with(['coop', 'brand', 'category', 'supplier'])->paginate(20);

        // Kembalikan view dengan data yang diperlukan
        return view('products.filter', compact('products', 'coops', 'brands', 'categories', 'suppliers'));
    }

    /**
     * Method untuk mendownload Excel berdasarkan filter
     */
    public function downloadExcel(Request $request)
    {
        // Log untuk memeriksa akses method
        Log::info('Download Excel Route Accessed', $request->all());

        // Validasi input filter
        $request->validate([
            'start_date'    => 'nullable|string',
            'end_date'      => 'nullable|string',
            'coop_id'       => 'nullable|exists:coops,id',
            'product_name'  => 'nullable|string|max:255',
            'category_id'   => 'nullable|exists:categories,id',
            'brand_id'      => 'nullable|exists:brands,id',
            'supplier_id'   => 'nullable|exists:suppliers,id',
        ]);

        // Fungsi untuk mengubah format tanggal dari dd/mm/yyyy ke yyyy-mm-dd
        $formatTanggal = function ($tanggal) {
            if ($tanggal) {
                try {
                    return Carbon::createFromFormat('d/m/Y', $tanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Log error parsing tanggal
                    Log::error('Error parsing date: ' . $tanggal, ['error' => $e->getMessage()]);
                    // Kembalikan respon dengan error
                    abort(400, 'Format tanggal tidak valid untuk ' . $tanggal . '. Harap gunakan format dd/mm/yyyy.');
                }
            }
            return null;
        };

        // Ambil dan ubah format filter tanggal
        $filters = $request->only(['start_date', 'end_date', 'coop_id', 'product_name', 'category_id', 'brand_id', 'supplier_id']);
        if (!empty($filters['start_date'])) {
            $filters['start_date'] = $formatTanggal($filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $filters['end_date'] = $formatTanggal($filters['end_date']);
        }

        // Export Excel dengan filter menggunakan ProductsExport
        return Excel::download(new ProductsExport($filters), 'products.xlsx');
    }
}
