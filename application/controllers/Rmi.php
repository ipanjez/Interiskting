<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rmi extends CI_Controller
{    function __construct()
    {
        parent::__construct();
        $c_url = $this->router->fetch_class();
        $this->layout->auth();
        $this->layout->auth_privilege($c_url);
        // $this->load->model('Kpi_model'); // Commented out - not needed for RMI
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->database(); // Load database library for RMI operations
    }    public function index()
    {
        // Debug: Check if we reach this point
        error_log("RMI Controller: index() method called");
        
        $data['title'] = 'Risk Management Index (RMI)';
        $data['subtitle'] = 'Assessment Dashboard';
        $data['crumb'] = [
            'Rmi' => '',
        ];

        // Read and parse CSV data
        $csv_path = FCPATH . 'List RMI.csv';
        error_log("RMI Controller: CSV path = " . $csv_path);
        
        $rmi_data = $this->parse_rmi_csv($csv_path);
        error_log("RMI Controller: Parsed data - " . count($rmi_data['structured_data']) . " dimensions");
        
        // Populate parameters table for better performance
        $this->populate_parameters_table($rmi_data['structured_data'], $rmi_data['total_points']);
        
        $data['rmi_data'] = $rmi_data['structured_data'];
        $data['total_points'] = $rmi_data['total_points'];        $data['code_js'] = 'rmi/codejs';
        $data['page'] = 'rmi/rmi_view';
        
        error_log("RMI Controller: Loading template/backend view");
        $this->load->view('template/backend', $data);
        error_log("RMI Controller: View loaded successfully");
    }

    private function parse_rmi_csv($csv_path)
    {
        $structured_data = [];
        $total_points = [];
        
        if (!file_exists($csv_path)) {
            return ['structured_data' => $structured_data, 'total_points' => $total_points];
        }

        $file = fopen($csv_path, 'r');
        $header = fgetcsv($file); // Skip header row
        
        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($row) >= 7) {
                $dimensi = trim($row[0]);
                $sub_dimensi = trim($row[1]);
                $parameter = trim($row[2]);
                
                // Count total checklist points across all phases
                $total_count = 0;
                for ($i = 3; $i <= 7; $i++) {
                    $phase_content = trim($row[$i]);
                    // Count bullet points (•) in each phase
                    $total_count += substr_count($phase_content, '•');
                }
                
                // Structure the data
                if (!isset($structured_data[$dimensi])) {
                    $structured_data[$dimensi] = [];
                }
                
                if (!isset($structured_data[$dimensi][$sub_dimensi])) {
                    $structured_data[$dimensi][$sub_dimensi] = [];
                }
                
                $structured_data[$dimensi][$sub_dimensi][] = [
                    'parameter' => $parameter,
                    'phases' => [
                        '1' => trim($row[3]),
                        '2' => trim($row[4]),
                        '3' => trim($row[5]),
                        '4' => trim($row[6]),
                        '5' => trim($row[7])
                    ]
                ];
                
                // Store total points count for this parameter
                $parameter_key = md5($dimensi . $sub_dimensi . $parameter);
                $total_points[$parameter_key] = $total_count;
            }
        }
        
        fclose($file);
        
        return ['structured_data' => $structured_data, 'total_points' => $total_points];
    }    public function json()
    {
        header('Content-Type: application/json');
        // This method was originally for KPI data, not needed for RMI
        // echo $this->Kpi_model->json();
        
        // Return empty JSON for now, or implement RMI-specific JSON data if needed
        echo json_encode(['status' => 'success', 'message' => 'RMI JSON endpoint']);
    }

    public function save_point_progress()
    {
        // Set JSON header
        $this->output->set_content_type('application/json');
        
        // Validate AJAX request
        if (!$this->input->is_ajax_request()) {
            $this->output->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request']));
            return;
        }

        // Get POST data
        $parameter_key = $this->input->post('parameter_key');
        $phase_level = $this->input->post('phase_level');
        $point_identifier = $this->input->post('point_identifier');
        $point_text = $this->input->post('point_text');
        $is_completed = $this->input->post('is_completed') ? 1 : 0;
        
        // Validate required fields
        if (empty($parameter_key) || empty($phase_level) || empty($point_identifier)) {
            $this->output->set_output(json_encode(['status' => 'error', 'message' => 'Missing required fields']));
            return;
        }

        // Get current user ID (assuming ion_auth is being used)
        $user_id = $this->ion_auth->user()->row()->id;

        // Prepare data for database
        $data = [
            'parameter_key' => $parameter_key,
            'phase_level' => (int)$phase_level,
            'point_identifier' => $point_identifier,
            'point_text' => $point_text,
            'is_completed' => $is_completed,
            'completed_by' => $user_id,
            'completed_at' => $is_completed ? date('Y-m-d H:i:s') : null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Check if record exists
        $existing = $this->db->get_where('rmi_point_progress', [
            'parameter_key' => $parameter_key,
            'phase_level' => $phase_level,
            'point_identifier' => $point_identifier
        ])->row();

        try {
            if ($existing) {
                // Update existing record
                $this->db->where([
                    'parameter_key' => $parameter_key,
                    'phase_level' => $phase_level,
                    'point_identifier' => $point_identifier
                ]);
                $result = $this->db->update('rmi_point_progress', $data);
            } else {
                // Insert new record
                $data['created_at'] = date('Y-m-d H:i:s');
                $result = $this->db->insert('rmi_point_progress', $data);
            }

            if ($result) {
                // Calculate progress for this parameter
                $progress = $this->calculate_parameter_progress($parameter_key);
                
                $this->output->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Progress saved successfully',
                    'progress' => $progress
                ]));
            } else {
                $this->output->set_output(json_encode(['status' => 'error', 'message' => 'Failed to save progress']));
            }
        } catch (Exception $e) {
            $this->output->set_output(json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]));
        }
    }

    private function calculate_parameter_progress($parameter_key)
    {
        // Get total completed points for this parameter
        $completed_count = $this->db->where([
            'parameter_key' => $parameter_key,
            'is_completed' => 1
        ])->count_all_results('rmi_point_progress');

        // Get total points for this parameter from our stored data
        $total_points = $this->db->select('total_points')
            ->where('parameter_key', $parameter_key)
            ->get('rmi_parameters')
            ->row();

        if ($total_points) {
            $total = $total_points->total_points;
        } else {
            // Fallback: count all points for this parameter
            $total = $this->db->where('parameter_key', $parameter_key)
                ->count_all_results('rmi_point_progress');
        }

        $percentage = $total > 0 ? round(($completed_count / $total) * 100, 2) : 0;

        return [
            'completed' => $completed_count,
            'total' => $total,
            'percentage' => $percentage
        ];
    }

    public function get_saved_progress()
    {
        // Set JSON header
        $this->output->set_content_type('application/json');
        
        // Get all saved progress
        $progress_data = $this->db->select('parameter_key, phase_level, point_identifier, is_completed')
            ->where('is_completed', 1)
            ->get('rmi_point_progress')
            ->result_array();

        $formatted_progress = [];
        foreach ($progress_data as $item) {
            $key = $item['parameter_key'] . '_' . $item['phase_level'] . '_' . $item['point_identifier'];
            $formatted_progress[$key] = true;
        }

        $this->output->set_output(json_encode([
            'status' => 'success',
            'progress' => $formatted_progress
        ]));
    }

    private function get_phase_name($phase_number)
    {
        $phase_names = [
            1 => 'Fase Awal (Initial Phase)',
            2 => 'Fase Berkembang (Emerging Phase)', 
            3 => 'Fase Praktik yang Baik (Good Practice Phase)',
            4 => 'Fase Praktik yang Lebih Baik (Strong Practice Phase)',
            5 => 'Fase Praktik Terbaik (Best Practice Phase)'
        ];
        
        return isset($phase_names[$phase_number]) ? $phase_names[$phase_number] : 'Unknown Phase';
    }

    private function populate_parameters_table($structured_data, $total_points)
    {
        foreach ($structured_data as $dimensi => $sub_dimensi_array) {
            foreach ($sub_dimensi_array as $sub_dimensi => $parameters) {
                foreach ($parameters as $param_data) {
                    $parameter_key = md5($dimensi . $sub_dimensi . $param_data['parameter']);
                    $total_count = isset($total_points[$parameter_key]) ? $total_points[$parameter_key] : 0;
                    
                    // Check if parameter already exists
                    $existing = $this->db->get_where('rmi_parameters', ['parameter_key' => $parameter_key])->row();
                    
                    $data = [
                        'parameter_key' => $parameter_key,
                        'dimensi' => $dimensi,
                        'sub_dimensi' => $sub_dimensi,
                        'parameter' => $param_data['parameter'],
                        'total_points' => $total_count,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    if ($existing) {
                        $this->db->where('parameter_key', $parameter_key);
                        $this->db->update('rmi_parameters', $data);
                    } else {
                        $data['created_at'] = date('Y-m-d H:i:s');
                        $this->db->insert('rmi_parameters', $data);
                    }
                    
                    // Also populate point progress table with all possible points
                    $this->populate_point_progress_table($parameter_key, $param_data['phases']);
                }
            }
        }
    }

    private function populate_point_progress_table($parameter_key, $phases)
    {
        foreach ($phases as $phase_level => $phase_content) {
            $phase_points = explode('•', $phase_content);
            $phase_points = array_filter(array_map('trim', $phase_points));
            
            foreach ($phase_points as $point) {
                if (!empty($point)) {
                    $point_identifier = md5($point);
                    
                    // Check if point already exists
                    $existing = $this->db->get_where('rmi_point_progress', [
                        'parameter_key' => $parameter_key,
                        'phase_level' => $phase_level,
                        'point_identifier' => $point_identifier
                    ])->row();
                    
                    if (!$existing) {
                        $data = [
                            'parameter_key' => $parameter_key,
                            'phase_level' => $phase_level,
                            'point_identifier' => $point_identifier,
                            'point_text' => $point,
                            'is_completed' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->insert('rmi_point_progress', $data);
                    }
                }
            }
        }
    }
}

/* End of file Grafik.php */