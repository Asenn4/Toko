<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\ProductModel;
use Dompdf\Dompdf;

class ProdukController extends BaseController
{

    protected $productModel; 

    function __construct() {
        $this->productModel = new ProductModel();
    }
    public function index()
    {
        return view('produk/index', [
            'products' => $this->productModel->findAll()
        ]);
    }

    public function create() {
        $dataFoto = $this->request->getFile('foto');

        $dataForm = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'jumlah' => $this->request->getPost('jumlah') 
        ];

        if ($dataFoto->isValid()) {
            $fileName = $dataFoto->getRandomName(); 
            $dataFoto->move('img/', $fileName);
            
            // Compress image untuk menghemat memory
            $this->compressImage('img/' . $fileName);
            
            $dataForm['foto'] = $fileName;
        }

        $this->productModel->insert($dataForm);

        return redirect('produk')->with('success', 'Data Berhasil Ditambah');
    } 

    public function edit($id) {
        $dataProduk = $this->productModel->find($id);

        $dataForm = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'jumlah' => $this->request->getPost('jumlah') 
        ];

        if ($this->request->getPost('check') == 1) {
            if ($dataProduk['foto'] != '' and file_exists("img/" . $dataProduk['foto'] . "")) {
                unlink("img/" . $dataProduk['foto']);
            }

            $dataFoto = $this->request->getFile('foto');

            if ($dataFoto->isValid()) {
                $fileName = $dataFoto->getRandomName();
                $dataFoto->move('img/', $fileName);
                
                // Compress image untuk menghemat memory
                $this->compressImage('img/' . $fileName);
                
                $dataForm['foto'] = $fileName;
            }
        }

        $this->productModel->update($id, $dataForm);

        return redirect('produk')->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id) {
        $dataProduk = $this->productModel->find($id);
        $this->productModel->delete($id);

        return redirect('produk')->with('success', 'Data Berhasil Dihapus');
    }
    
    public function download() {
        // Boost memory limit untuk proses PDF
        $oldMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '1024M');
        
        try {
            // Ambil data produk dari database
            $products = $this->productModel->findAll();

            // Render view menjadi HTML
            $html = view('produk/download_pdf', [
                'products' => $products
            ]);

            // Nama file PDF
            $filename = date('Y-m-d-H-i-s') . '-produk.pdf';

            // Inisialisasi Dompdf dengan opsi memory yang lebih efisien
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', false);
            $options->set('tempDir', WRITEPATH . 'uploads');
            $dompdf = new Dompdf($options);

            // Load HTML ke Dompdf
            $dompdf->loadHtml($html);

            // Setting ukuran kertas dan orientasi
            $dompdf->setPaper('A4', 'portrait');

            // Generate PDF
            $dompdf->render();

            // Download / tampilkan PDF
            $dompdf->stream($filename, [
                'Attachment' => true
            ]);
        } finally {
            // Restore memory limit
            ini_set('memory_limit', $oldMemoryLimit);
        }
    }

    /**
     * Compress & Resize image untuk menghemat memory
     * 
     * @param string $imagePath Path ke file gambar
     * @param int $maxWidth Max width untuk resize (pixel)
     * @param int $quality Quality kompresi (1-100)
     */
    private function compressImage($imagePath, $maxWidth = 500, $quality = 50) {
        if (!file_exists($imagePath)) {
            return;
        }

        $imageInfo = @getimagesize($imagePath);
        if ($imageInfo === false) {
            return;
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mime = $imageInfo['mime'];
        
        // Hitung dimensi baru jika perlu di-resize
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;
        
        if ($originalWidth > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = intval(($originalHeight / $originalWidth) * $maxWidth);
        }

        // Load image
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            default:
                return;
        }

        // Create resized image
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        // Save dengan kompresi
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($resized, $imagePath, $quality);
                break;
            case 'image/png':
                imagepng($resized, $imagePath, 8);
                break;
            case 'image/gif':
                imagegif($resized, $imagePath);
                break;
        }

        // Cleanup
        imagedestroy($image);
        imagedestroy($resized);
    }
}
