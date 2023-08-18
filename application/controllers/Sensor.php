<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sensor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sensor_model');
    }

    public function index()
    {
        $data['sensor_data'] = $this->sensor_model->get_sensor_data();
        $this->load->view('sensor_view', $data);
    }

    public function send_sensor_data()
    {
        if ($this->input->post('ldrValue')) {
            $ldrValue = $this->input->post('ldrValue');
            $this->sensor_model->insert_sensor_data($ldrValue); // Memasukkan data sensor ke dalam database

            // Respons HTTP untuk memberi tahu Arduino bahwa data berhasil diterima
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success']));
        } else {
            // Respons HTTP jika data tidak diterima dengan benar
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error']));
        }
    }

    public function get_sensor_data()
    {
        $sensor_data = $this->sensor_model->get_sensor_data();
        // Return the data as JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($sensor_data));
    }
}
?>