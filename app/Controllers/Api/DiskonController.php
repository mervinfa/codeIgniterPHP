<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class DiskonController extends ResourceController
{
    protected $modelName = 'App\Models\DiscountModel'; 
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    public function show($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound('Data Diskon tidak ditemukan.');
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        
        if (empty($data)) {
            $data = $this->request->getPost();
        }

        if (empty($data)) {
            return $this->fail('Gagal: Data yang dikirim kosong atau format Header tidak sesuai.');
        }

        if ($this->model->insert($data)) {
            return $this->respondCreated(['message' => 'Data Diskon berhasil ditambahkan']);
        }
        return $this->failValidationErrors($this->model->errors());
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        
        if (empty($data)) {
            $data = $this->request->getRawInput();
        }

        if (empty($data)) {
            return $this->fail('Gagal: Data yang dikirim kosong.');
        }

        if (!$this->model->find($id)) {
            return $this->failNotFound('Data Diskon yang ingin diubah tidak ditemukan.');
        }

        if ($this->model->update($id, $data)) {
            return $this->respond(['message' => 'Data Diskon berhasil diubah']);
        }
        return $this->failValidationErrors($this->model->errors());
    }

    public function delete($id = null)
    {
        if ($this->model->find($id)) {
            $this->model->delete($id);
            return $this->respondDeleted(['message' => 'Data Diskon berhasil dihapus']);
        }
        return $this->failNotFound('Data Diskon yang ingin dihapus tidak ditemukan.');
    }
}