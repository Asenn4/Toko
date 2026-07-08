<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;

class DiskonController extends BaseController
{
    protected $discountModel; 

    function __construct() {
        $this->discountModel = new DiscountModel();
    }
    
    public function index()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/');
        }
        
        return view('diskon/index', [
            'discounts' => $this->discountModel->orderBy('tanggal', 'ASC')->findAll()
        ]);
    }

    public function create() {
        if (session()->get('role') != 'admin') return redirect()->to('/');
        
        $tanggal = $this->request->getPost('tanggal');
        
        // Validation: Unique Date
        $existing = $this->discountModel->where('tanggal', $tanggal)->first();
        if ($existing) {
            return redirect()->to('diskon')->with('error', 'The tanggal field must contain a unique value.');
        }

        $dataForm = [
            'tanggal' => $tanggal,
            'nominal' => $this->request->getPost('nominal')
        ];

        $this->discountModel->insert($dataForm);

        return redirect()->to('diskon')->with('success', 'Data Berhasil Ditambah');
    } 

    public function edit($id) {
        if (session()->get('role') != 'admin') return redirect()->to('/');
        
        $dataForm = [
            'nominal' => $this->request->getPost('nominal')
            // tanggal is readonly, so we do not update it
        ];

        $this->discountModel->update($id, $dataForm);

        return redirect()->to('diskon')->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id) {
        if (session()->get('role') != 'admin') return redirect()->to('/');
        
        $this->discountModel->delete($id);

        return redirect()->to('diskon')->with('success', 'Data Berhasil Dihapus');
    }
}
