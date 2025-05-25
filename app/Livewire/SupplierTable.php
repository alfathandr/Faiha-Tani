<?php

namespace App\Livewire;

use Livewire\Component;
// use App\Models\Product; // Tidak digunakan di sini, bisa dihapus jika tidak ada logika produk
use App\Models\Supplier;
use Illuminate\Support\Facades\DB; // Gunakan facade DB
use Livewire\WithPagination; // Tambahkan ini untuk fungsionalitas pagination

class SupplierTable extends Component
{
    use WithPagination; // Gunakan trait WithPagination

    public $name, $contact, $address;
    public $selectedSupplier;
    public $showEditForm = false;
    public $showAddForm = false;
    public $showDeleteModal = false;
    public $supplierIdToDelete;
    public $supplierNameToDelete;
    public $search = '';

    // Properti untuk pagination (opsional jika ingin override default Livewire)
    public $perPage = 10;

    // Menambahkan aturan validasi properti
    protected $rules = [
        'name' => 'required|string|max:255',
        'contact' => 'nullable|string|max:500', // Batasi panjang string
        'address' => 'nullable|string|max:1000', // Batasi panjang string
    ];

    // Lifecycle hook Livewire: reset halaman saat pencarian berubah
    public function updatedSearch()
    {
        $this->resetPage(); // Metode dari WithPagination
    }

    // Lifecycle hook Livewire: reset halaman saat jumlah per halaman berubah
    public function updatedPerPage()
    {
        $this->resetPage(); // Metode dari WithPagination
    }

    /**
     * Menyimpan supplier baru ke database.
     */
    public function addSupplier()
    {
        // Validasi input menggunakan rules yang sudah didefinisikan di properti $rules
        $this->validate();

        try {
            DB::beginTransaction(); // Mulai transaksi

            Supplier::create([
                'name' => $this->name,
                // PERBAIKAN PENTING: Menggunakan $this->contact dan $this->address
                'contact' => $this->contact,
                'address' => $this->address,
            ]);

            DB::commit(); // Simpan perubahan ke database

            session()->flash('message', 'Supplier berhasil ditambahkan.');
            $this->resetFormAndCloseModals(); // Reset form dan tutup modal/form
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error
            // Log error untuk debugging lebih lanjut
            \Log::error('Error adding supplier: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menambahkan supplier: ' . $e->getMessage());
        }
    }

    /**
     * Mengisi form edit dengan data supplier yang dipilih.
     *
     * @param int $id ID dari supplier yang akan diedit
     */
    public function update($id) // Nama fungsi tetap 'update' sesuai permintaan
    {
        $this->selectedSupplier = Supplier::findOrFail($id);
        $this->name = $this->selectedSupplier->name;
        $this->contact = $this->selectedSupplier->contact;
        $this->address = $this->selectedSupplier->address;

        $this->showEditForm = true;
        $this->showAddForm = false; // Pastikan form tambah tertutup saat mengedit
    }

    /**
     * Memperbarui data supplier yang sudah ada di database.
     */
    public function updateSupplier()
    {
        // Pastikan ada supplier yang dipilih untuk diperbarui
        if (!$this->selectedSupplier) {
            session()->flash('error', 'Tidak ada supplier yang dipilih untuk diperbarui.');
            return;
        }

        // Validasi input
        $this->validate();

        try {
            $this->selectedSupplier->update([
                'name' => $this->name,
                // PERBAIKAN PENTING: Menggunakan $this->contact dan $this->address
                'contact' => $this->contact,
                'address' => $this->address,
            ]);

            session()->flash('message', 'Supplier berhasil diperbarui.');
            $this->resetFormAndCloseModals(); // Reset form dan tutup modal/form
        } catch (\Exception $e) {
            // Log error untuk debugging lebih lanjut
            \Log::error('Error updating supplier ID ' . $this->selectedSupplier->id . ': ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui supplier: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan modal konfirmasi penghapusan.
     *
     * @param int $id ID dari supplier yang akan dihapus
     */
    public function confirmDelete($id)
    {
        $supplier = Supplier::find($id);

        if ($supplier) {
            $this->supplierIdToDelete = $id;
            $this->supplierNameToDelete = $supplier->name;
            $this->showDeleteModal = true; // Tampilkan modal
        } else {
            session()->flash('error', 'Supplier tidak ditemukan untuk dihapus.');
        }
    }

    /**
     * Menghapus supplier dari database.
     */
    public function delete() // Nama fungsi tetap 'delete' sesuai permintaan
    {
        if ($this->supplierIdToDelete) {
            try {
                $supplier = Supplier::findOrFail($this->supplierIdToDelete);

                // Opsi: Cek apakah supplier memiliki produk terkait sebelum menghapus.
                // Jika Anda ingin mencegah penghapusan jika ada produk terkait (onDelete('restrict') di migrasi),
                // maka bagian ini penting. Jika onDelete('set null') di migrasi sudah cukup,
                // Anda bisa menghapus bagian if ini.
                if ($supplier->products()->count() > 0) {
                     session()->flash('error', 'Supplier tidak dapat dihapus karena masih memiliki produk terkait.');
                     $this->showDeleteModal = false;
                     $this->reset(['supplierIdToDelete', 'supplierNameToDelete']);
                     return;
                }

                $supplier->delete();
                session()->flash('message', 'Supplier "' . $this->supplierNameToDelete . '" berhasil dihapus.');
                $this->resetPage(); // Reset halaman setelah penghapusan untuk menyegarkan daftar
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                session()->flash('error', 'Supplier tidak ditemukan.');
            } catch (\Exception $e) {
                // Log error untuk debugging lebih lanjut
                \Log::error('Error deleting supplier ID ' . $this->supplierIdToDelete . ': ' . $e->getMessage());
                session()->flash('error', 'Terjadi kesalahan saat menghapus supplier: ' . $e->getMessage());
            }
        }

        // Reset modal setelah operasi
        $this->showDeleteModal = false;
        $this->supplierIdToDelete = null;
        $this->supplierNameToDelete = null;
    }

    /**
     * Private function untuk mereset properti form dan menutup modal.
     * Mengganti nama dari resetForm() agar lebih deskriptif dan serbaguna.
     */
    private function resetFormAndCloseModals()
    {
        $this->reset(['name', 'contact', 'address', 'selectedSupplier', 'showEditForm', 'showAddForm', 'showDeleteModal', 'supplierIdToDelete', 'supplierNameToDelete']);
    }

    /**
     * Render komponen Livewire.
     */
    public function render()
    {
        $suppliers = Supplier::query()
            ->when($this->search, function ($query) {
                // Mencari berdasarkan nama, kontak, atau alamat
                return $query->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('contact', 'like', '%' . $this->search . '%')
                             ->orWhere('address', 'like', '%' . $this->search . '%');
            })
            ->latest() // Urutkan berdasarkan yang terbaru
            ->paginate($this->perPage); // Gunakan pagination

        return view('livewire.supplier-table', [
            'suppliers' => $suppliers,
        ]);
    }
}