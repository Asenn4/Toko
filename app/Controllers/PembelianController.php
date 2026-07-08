<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class PembelianController extends BaseController
{
    protected $transactionModel;
    protected $transactionDetailModel;

    function __construct() {
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
    }
    
    public function index()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/');
        }
        
        $transactions = $this->transactionModel->findAll();
        $transactionIds = array_column($transactions, 'id');

        $products = [];
        if (!empty($transactionIds)) {
            $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);
        }
    
        $data = [
            'transactions'  => $transactions,
            'products'      => $products
        ]; 
        
        return view('pembelian/index', $data);
    }

    public function update_status($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/');
        }
        
        $status = $this->request->getPost('status');
        
        $this->transactionModel->update($id, ['status' => $status]);

        return redirect()->to('pembelian')->with('success', 'Status Berhasil Diubah');
    }
}
