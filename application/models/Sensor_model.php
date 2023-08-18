<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sensor_model extends CI_Model
{

    public function insert_sensor_data($ldrValue)
    {
        $data = array(
            'ldr_value' => $ldrValue
        );
        $this->db->insert('ldr_data', $data);
    }
    public function get_sensor_data()
    {
        $query = $this->db->get('ldr_data');
        return $query->result();
        // $this->db->order_by('id', 'ASC'); // Assuming 'id' is your primary key
        // $query = $this->db->get('ldr_data', 10); // 'your_table' is the name of your table
        // return $query->result();
    }
}
?>