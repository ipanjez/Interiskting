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
        
        // Calculate dimension and sub-dimension summaries
        $dimension_summaries = $this->calculate_dimension_summaries($rmi_data['structured_data'], $rmi_data['total_points']);
        
        $data['rmi_data'] = $rmi_data['structured_data'];
        $data['total_points'] = $rmi_data['total_points'];
        $data['dimension_summaries'] = $dimension_summaries;
        $data['code_js'] = 'rmi/codejs';
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
    }    public function save_point_progress()
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
            $this->output->set_output(json_encode([
                'status' => 'error', 
                'message' => 'Missing required fields',
                'debug' => [
                    'parameter_key' => $parameter_key,
                    'phase_level' => $phase_level,
                    'point_identifier' => $point_identifier
                ]
            ]));
            return;
        }

        // Get current user ID (try ion_auth, fallback to session or default)
        $user_id = 1; // Default fallback
        try {
            if ($this->ion_auth && $this->ion_auth->logged_in()) {
                $user = $this->ion_auth->user()->row();
                if ($user) {
                    $user_id = $user->id;
                }
            }
        } catch (Exception $e) {
            // Continue with default user_id if ion_auth fails
            error_log('Ion Auth error in save_point_progress: ' . $e->getMessage());
        }

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

        try {
            // First, ensure the tables exist by trying to create them if they don't exist
            $this->ensure_rmi_tables_exist();
            
            // Check if record exists with proper error handling
            $this->db->where([
                'parameter_key' => $parameter_key,
                'phase_level' => $phase_level,
                'point_identifier' => $point_identifier
            ]);
            $existing = $this->db->get('rmi_point_progress')->row();

            if ($existing) {
                // Update existing record
                $this->db->where([
                    'parameter_key' => $parameter_key,
                    'phase_level' => $phase_level,
                    'point_identifier' => $point_identifier
                ]);
                $result = $this->db->update('rmi_point_progress', $data);
                $operation = 'update';
            } else {
                // Insert new record
                $data['created_at'] = date('Y-m-d H:i:s');
                $result = $this->db->insert('rmi_point_progress', $data);
                $operation = 'insert';
            }

            // Check for database errors
            if ($this->db->error()['code'] !== 0) {
                throw new Exception('Database error: ' . $this->db->error()['message']);
            }

            if ($result) {
                // Calculate progress for this parameter
                $progress = $this->calculate_parameter_progress($parameter_key);
                
                $this->output->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Progress saved successfully',
                    'operation' => $operation,
                    'progress' => $progress
                ]));
            } else {
                $this->output->set_output(json_encode([
                    'status' => 'error', 
                    'message' => 'Failed to save progress - no rows affected',
                    'debug' => [
                        'operation' => $operation ?? 'unknown',
                        'db_error' => $this->db->error()
                    ]
                ]));
            }
        } catch (Exception $e) {
            error_log('RMI save_point_progress error: ' . $e->getMessage());
            $this->output->set_output(json_encode([
                'status' => 'error', 
                'message' => 'Database error: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ]));
        }
    }
    
    /**
     * Ensure RMI tables exist in database
     */
    private function ensure_rmi_tables_exist()
    {
        // Check if rmi_point_progress table exists
        if (!$this->db->table_exists('rmi_point_progress')) {
            // Create the table
            $sql = "CREATE TABLE IF NOT EXISTS `rmi_point_progress` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `parameter_key` varchar(255) NOT NULL,
                `phase_level` tinyint(4) NOT NULL,
                `point_identifier` varchar(255) NOT NULL,
                `point_text` text NOT NULL,
                `is_completed` tinyint(1) NOT NULL DEFAULT 0,
                `completed_by` int(11) DEFAULT NULL,
                `completed_at` timestamp NULL DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_point_progress` (`parameter_key`, `phase_level`, `point_identifier`),
                KEY `idx_parameter_key` (`parameter_key`),
                KEY `idx_phase_level` (`phase_level`),
                KEY `idx_is_completed` (`is_completed`),
                KEY `idx_completed_by` (`completed_by`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $this->db->query($sql);
        }
        
        // Check if rmi_parameters table exists
        if (!$this->db->table_exists('rmi_parameters')) {
            $sql = "CREATE TABLE IF NOT EXISTS `rmi_parameters` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `parameter_key` varchar(255) NOT NULL,
                `dimensi` varchar(255) NOT NULL,
                `sub_dimensi` varchar(255) NOT NULL,
                `parameter` text NOT NULL,
                `total_points` int(11) NOT NULL DEFAULT 0,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_parameter_key` (`parameter_key`),
                KEY `idx_dimensi` (`dimensi`),
                KEY `idx_sub_dimensi` (`sub_dimensi`),
                KEY `idx_dimensi_sub` (`dimensi`, `sub_dimensi`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $this->db->query($sql);
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
        
        // Validate AJAX request
        if (!$this->input->is_ajax_request()) {
            $this->output->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request']));
            return;
        }

        try {
            // Get all completed progress records
            $progress_data = $this->db->select('parameter_key, phase_level, point_identifier, point_text, is_completed')
                ->where('is_completed', 1)
                ->get('rmi_point_progress')
                ->result_array();

            $this->output->set_output(json_encode([
                'status' => 'success',
                'data' => $progress_data
            ]));
        } catch (Exception $e) {
            $this->output->set_output(json_encode([
                'status' => 'error', 
                'message' => 'Database error: ' . $e->getMessage()
            ]));
        }
    }

    public function get_dimension_progress()
    {
        // Set JSON header
        $this->output->set_content_type('application/json');
        
        $dimension_name = $this->input->get('dimension');
        
        if (empty($dimension_name)) {
            $this->output->set_output(json_encode(['status' => 'error', 'message' => 'Dimension name required']));
            return;
        }
        
        // Get all parameters for this dimension
        $parameters = $this->db->select('parameter_key, total_points')
            ->where('dimensi', $dimension_name)
            ->get('rmi_parameters')
            ->result_array();
        
        $total_points = 0;
        $completed_points = 0;
        $total_parameters = count($parameters);
        $parameter_scores = [];
        $sub_dimensions = [];
        
        // Get unique sub-dimensions
        $sub_dim_query = $this->db->select('DISTINCT sub_dimensi')
            ->where('dimensi', $dimension_name)
            ->get('rmi_parameters')
            ->result_array();
            
        foreach ($sub_dim_query as $row) {
            $sub_dimensions[] = $row['sub_dimensi'];
        }
        
        foreach ($parameters as $param) {
            $total_points += $param['total_points'];
            
            // Get completed points for this parameter
            $completed = $this->db->where([
                'parameter_key' => $param['parameter_key'],
                'is_completed' => 1
            ])->count_all_results('rmi_point_progress');
            
            $completed_points += $completed;
            
            // Calculate parameter score
            $parameter_scores[] = $this->calculate_parameter_score_from_db($param['parameter_key']);
        }
        
        $progress_percentage = $total_points > 0 ? round(($completed_points / $total_points) * 100, 2) : 0;
        $average_score = count($parameter_scores) > 0 ? round(array_sum($parameter_scores) / count($parameter_scores), 2) : 0;
        
        $this->output->set_output(json_encode([
            'status' => 'success',
            'dimension' => $dimension_name,
            'total_parameters' => $total_parameters,
            'total_sub_dimensions' => count($sub_dimensions),
            'sub_dimensions' => $sub_dimensions,
            'total_points' => $total_points,
            'completed_points' => $completed_points,
            'progress_percentage' => $progress_percentage,
            'average_score' => $average_score
        ]));
    }
    
    public function get_subdimension_progress()
    {
        // Set JSON header
        $this->output->set_content_type('application/json');
        
        $dimension_name = $this->input->get('dimension');
        $subdimension_name = $this->input->get('subdimension');
        
        if (empty($dimension_name) || empty($subdimension_name)) {
            $this->output->set_output(json_encode(['status' => 'error', 'message' => 'Dimension and sub-dimension names required']));
            return;
        }
        
        // Get all parameters for this sub-dimension
        $parameters = $this->db->select('parameter_key, total_points')
            ->where('dimensi', $dimension_name)
            ->where('sub_dimensi', $subdimension_name)
            ->get('rmi_parameters')
            ->result_array();
        
        $total_points = 0;
        $completed_points = 0;
        $total_parameters = count($parameters);
        $parameter_scores = [];
        
        foreach ($parameters as $param) {
            $total_points += $param['total_points'];
            
            // Get completed points for this parameter
            $completed = $this->db->where([
                'parameter_key' => $param['parameter_key'],
                'is_completed' => 1
            ])->count_all_results('rmi_point_progress');
            
            $completed_points += $completed;
            
            // Calculate parameter score
            $parameter_scores[] = $this->calculate_parameter_score_from_db($param['parameter_key']);
        }
        
        $progress_percentage = $total_points > 0 ? round(($completed_points / $total_points) * 100, 2) : 0;
        $average_score = count($parameter_scores) > 0 ? round(array_sum($parameter_scores) / count($parameter_scores), 2) : 0;
        
        $this->output->set_output(json_encode([
            'status' => 'success',
            'dimension' => $dimension_name,
            'subdimension' => $subdimension_name,
            'total_parameters' => $total_parameters,
            'total_points' => $total_points,
            'completed_points' => $completed_points,
            'progress_percentage' => $progress_percentage,
            'average_score' => $average_score
        ]));
    }
    
    private function calculate_parameter_score_from_db($parameter_key)
    {
        // Get all phase completion status for this parameter
        $phases_completion = [];
        
        for ($phase = 1; $phase <= 5; $phase++) {
            $total_in_phase = $this->db->where([
                'parameter_key' => $parameter_key,
                'phase_level' => $phase
            ])->count_all_results('rmi_point_progress');
            
            $completed_in_phase = $this->db->where([
                'parameter_key' => $parameter_key,
                'phase_level' => $phase,
                'is_completed' => 1
            ])->count_all_results('rmi_point_progress');
            
            $phases_completion[$phase] = ($total_in_phase > 0 && $completed_in_phase == $total_in_phase);
        }
        
        // Calculate score - highest completed phase
        $score = 0;
        for ($phase = 1; $phase <= 5; $phase++) {
            if ($phases_completion[$phase]) {
                $score = $phase;
            } else {
                break; // Stop at first incomplete phase
            }
        }
        
        return $score;
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

    private function calculate_dimension_summaries($structured_data, $total_points)
    {
        $dimension_summaries = [];
        
        foreach ($structured_data as $dimensi => $sub_dimensi_array) {            $dimension_summaries[$dimensi] = [
                'name' => $dimensi,
                'total_parameters' => 0,
                'total_points' => 0,
                'sub_dimensions' => [],
                'parameter_numbers' => []  // Add this for dimension-level parameter numbers
            ];
            
            foreach ($sub_dimensi_array as $sub_dimensi => $parameters) {                $sub_dimension_data = [
                    'name' => $sub_dimensi,
                    'parameter_count' => count($parameters),
                    'total_points' => 0,
                    'parameter_range' => [],
                    'parameter_numbers' => []  // Add this for the number range
                ];
                
                foreach ($parameters as $param_data) {
                    $parameter_key = md5($dimensi . $sub_dimensi . $param_data['parameter']);
                    $param_points = isset($total_points[$parameter_key]) ? $total_points[$parameter_key] : 0;
                    
                    $sub_dimension_data['total_points'] += $param_points;
                    $sub_dimension_data['parameter_range'][] = $param_data['parameter'];
                    
                    // Extract parameter number from parameter text
                    if (preg_match('/^\s*(\d+(?:\.\d+)?)\s*\.?\s*/', $param_data['parameter'], $matches)) {
                        $param_number = floatval($matches[1]);
                        if (!in_array($param_number, $sub_dimension_data['parameter_numbers'])) {
                            $sub_dimension_data['parameter_numbers'][] = $param_number;
                        }
                    }
                }
                  $dimension_summaries[$dimensi]['sub_dimensions'][$sub_dimensi] = $sub_dimension_data;
                $dimension_summaries[$dimensi]['total_parameters'] += $sub_dimension_data['parameter_count'];
                $dimension_summaries[$dimensi]['total_points'] += $sub_dimension_data['total_points'];
                
                // Add sub-dimension parameter numbers to dimension-level collection
                foreach ($sub_dimension_data['parameter_numbers'] as $param_num) {
                    if (!in_array($param_num, $dimension_summaries[$dimensi]['parameter_numbers'])) {
                        $dimension_summaries[$dimensi]['parameter_numbers'][] = $param_num;
                    }
                }
            }
        }
        
        return $dimension_summaries;
    }
    
    /**
     * Debug method to test database connection and table existence
     */
    public function debug_db()
    {
        $this->output->set_content_type('application/json');
        
        $debug_info = [];
        
        try {
            // Check database connection
            $debug_info['db_connected'] = $this->db->conn_id ? true : false;
            
            // Check if tables exist
            $debug_info['rmi_point_progress_exists'] = $this->db->table_exists('rmi_point_progress');
            $debug_info['rmi_parameters_exists'] = $this->db->table_exists('rmi_parameters');
            
            // Try to create tables
            $this->ensure_rmi_tables_exist();
            $debug_info['tables_created'] = 'attempted';
            
            // Check again after creation attempt
            $debug_info['rmi_point_progress_exists_after'] = $this->db->table_exists('rmi_point_progress');
            $debug_info['rmi_parameters_exists_after'] = $this->db->table_exists('rmi_parameters');
            
            // Get database error info
            $debug_info['db_error'] = $this->db->error();
            
            // Test simple insert
            $test_data = [
                'parameter_key' => 'test_key_' . time(),
                'phase_level' => 1,
                'point_identifier' => 'test_point',
                'point_text' => 'Test point',
                'is_completed' => 0,
                'completed_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $insert_result = $this->db->insert('rmi_point_progress', $test_data);
            $debug_info['test_insert'] = $insert_result;
            $debug_info['insert_error'] = $this->db->error();
            
            if ($insert_result) {
                // Clean up test data
                $this->db->where('parameter_key', $test_data['parameter_key']);
                $this->db->delete('rmi_point_progress');
            }
            
            $debug_info['status'] = 'success';
            
        } catch (Exception $e) {
            $debug_info['status'] = 'error';
            $debug_info['error_message'] = $e->getMessage();
            $debug_info['error_file'] = $e->getFile();
            $debug_info['error_line'] = $e->getLine();
        }
        
        $this->output->set_output(json_encode($debug_info, JSON_PRETTY_PRINT));
    }
    
    /**
     * Save performance aspects data via AJAX
     */
    public function save_performance_aspects()
    {
        // Set response headers
        $this->output->set_content_type('application/json');
        
        try {
            // Get POST data
            $final_rating = $this->input->post('final_rating');
            $risk_rating = $this->input->post('risk_rating');
            $final_rating_weight = $this->input->post('final_rating_weight');
            $risk_rating_weight = $this->input->post('risk_rating_weight');
            
            // Get current user ID
            $user_id = $this->ion_auth->get_user_id();
            if (!$user_id) {
                throw new Exception('User not authenticated');
            }
            
            // Prepare data for storage
            $save_data = [
                'final_rating' => $final_rating,
                'risk_rating' => $risk_rating,
                'final_rating_weight' => $final_rating_weight,
                'risk_rating_weight' => $risk_rating_weight,
                'user_id' => $user_id,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Check if record exists for this user
            $this->db->where('user_id', $user_id);
            $existing = $this->db->get('rmi_performance_aspects')->row();
            
            if ($existing) {
                // Update existing record
                $this->db->where('user_id', $user_id);
                $result = $this->db->update('rmi_performance_aspects', $save_data);
            } else {
                // Insert new record
                $save_data['created_at'] = date('Y-m-d H:i:s');
                $result = $this->db->insert('rmi_performance_aspects', $save_data);
            }
            
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' => 'Performance aspects saved successfully'
                ];
            } else {
                throw new Exception('Database operation failed: ' . print_r($this->db->error(), true));
            }
            
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Error saving performance aspects: ' . $e->getMessage()
            ];
            error_log("RMI Performance Aspects Save Error: " . $e->getMessage());
        }
        
        $this->output->set_output(json_encode($response));
    }
}

/* End of file Rmi.php */