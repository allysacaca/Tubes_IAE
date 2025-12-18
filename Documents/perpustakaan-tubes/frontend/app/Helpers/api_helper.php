<?php

/**
 * API Helper Functions
 * 
 * Collection of helper functions for API requests, formatting,
 * and session management for Library Management System
 * 
 * @package    LibraryManagement
 * @subpackage Helpers
 * @category   Helpers
 * @author     Your Name
 * @version    1.0.0
 */

// =====================================================
// API REQUEST FUNCTION
// =====================================================

if (!function_exists('api_request')) {
    /**
     * Make API request to Laravel backend
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $url API endpoint URL
     * @param array $data Request data
     * @param string|null $token Bearer token for authentication
     * @return array Response data
     * 
     * @example
     * $response = api_request('GET', 'http://localhost:8000/api/books');
     * $response = api_request('POST', 'http://localhost:8000/api/login', ['email' => 'test@example.com', 'password' => 'password']);
     */
    function api_request($method, $url, $data = [], $token = null)
    {
        $client = \Config\Services::curlrequest();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $options = [
            'headers' => $headers,
            'http_errors' => false,
            'verify' => false, // Disable SSL verification untuk development
            'timeout' => 30,   // Timeout 30 detik
            'connect_timeout' => 10, // Connection timeout 10 detik
        ];

        try {
            if ($method === 'GET') {
                // Untuk GET request, tambahkan query parameters jika ada
                if (!empty($data)) {
                    $url .= '?' . http_build_query($data);
                }
                $response = $client->get($url, $options);
            } elseif ($method === 'POST') {
                $options['body'] = json_encode($data);
                $response = $client->post($url, $options);
            } elseif ($method === 'PUT') {
                $options['body'] = json_encode($data);
                $response = $client->put($url, $options);
            } elseif ($method === 'DELETE') {
                if (!empty($data)) {
                    $options['body'] = json_encode($data);
                }
                $response = $client->delete($url, $options);
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid HTTP method: ' . $method,
                ];
            }

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            // Success response (2xx)
            if ($statusCode >= 200 && $statusCode < 300) {
                return [
                    'success' => true,
                    'data' => $body['data'] ?? $body,
                    'message' => $body['message'] ?? 'Success',
                    'status_code' => $statusCode,
                ];
            } 
            // Client error (4xx) or Server error (5xx)
            else {
                return [
                    'success' => false,
                    'message' => $body['message'] ?? 'Request failed',
                    'errors' => $body['errors'] ?? [],
                    'status_code' => $statusCode,
                ];
            }
        } catch (\Exception $e) {
            log_message('error', 'API Request Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
                'status_code' => 0,
            ];
        }
    }
}

// =====================================================
// DATE & TIME FORMATTING
// =====================================================

if (!function_exists('format_date')) {
    /**
     * Format date to Indonesian format
     *
     * @param string|null $date Date string
     * @param string $format Date format (default: 'd M Y')
     * @return string Formatted date or '-' if empty
     * 
     * @example
     * format_date('2024-12-18'); // Output: 18 Des 2024
     * format_date('2024-12-18', 'd F Y'); // Output: 18 Desember 2024
     */
    function format_date($date, $format = 'd M Y')
    {
        if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
            return '-';
        }

        $months = [
            'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar',
            'Apr' => 'Apr', 'May' => 'Mei', 'Jun' => 'Jun',
            'Jul' => 'Jul', 'Aug' => 'Agt', 'Sep' => 'Sep',
            'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Des',
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'June' => 'Juni', 'July' => 'Juli',
            'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober',
            'November' => 'November', 'December' => 'Desember'
        ];

        try {
            $timestamp = strtotime($date);
            if ($timestamp === false) {
                return '-';
            }

            $formatted = date($format, $timestamp);
            
            // Replace English months with Indonesian
            foreach ($months as $eng => $ind) {
                $formatted = str_replace($eng, $ind, $formatted);
            }

            return $formatted;
        } catch (\Exception $e) {
            log_message('error', 'Date formatting error: ' . $e->getMessage());
            return '-';
        }
    }
}

if (!function_exists('format_datetime')) {
    /**
     * Format datetime to Indonesian format
     *
     * @param string|null $datetime Datetime string
     * @param string $format Datetime format (default: 'd M Y H:i')
     * @return string Formatted datetime or '-' if empty
     * 
     * @example
     * format_datetime('2024-12-18 14:30:00'); // Output: 18 Des 2024 14:30
     */
    function format_datetime($datetime, $format = 'd M Y H:i')
    {
        return format_date($datetime, $format);
    }
}

// =====================================================
// CURRENCY FORMATTING
// =====================================================

if (!function_exists('format_currency')) {
    /**
     * Format number to Indonesian currency (Rupiah)
     *
     * @param float|int|string $amount Amount to format
     * @param bool $withSymbol Include 'Rp' symbol (default: true)
     * @return string Formatted currency
     * 
     * @example
     * format_currency(5000);        // Output: Rp 5.000
     * format_currency(1500000);     // Output: Rp 1.500.000
     * format_currency(5000, false); // Output: 5.000
     */
    function format_currency($amount, $withSymbol = true)
    {
        // Validasi dan convert ke angka
        $amount = is_numeric($amount) ? floatval($amount) : 0;
        
        $formatted = number_format($amount, 0, ',', '.');
        
        return $withSymbol ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('parse_currency')) {
    /**
     * Parse Indonesian currency format to number
     *
     * @param string $currency Currency string (e.g., "Rp 1.500.000")
     * @return float Parsed number
     * 
     * @example
     * parse_currency('Rp 1.500.000'); // Output: 1500000
     */
    function parse_currency($currency)
    {
        // Remove 'Rp', spaces, and dots
        $number = str_replace(['Rp', ' ', '.'], '', $currency);
        // Replace comma with dot for decimal
        $number = str_replace(',', '.', $number);
        
        return floatval($number);
    }
}

// =====================================================
// STATUS HELPERS
// =====================================================

if (!function_exists('get_status_badge')) {
    /**
     * Get Bootstrap badge class for status
     *
     * @param string $status Status string
     * @return string Bootstrap badge class (e.g., 'success', 'danger')
     * 
     * @example
     * get_status_badge('borrowed'); // Output: 'primary'
     * get_status_badge('overdue');  // Output: 'danger'
     */
    function get_status_badge($status)
    {
        $badges = [
            'active' => 'success',
            'inactive' => 'secondary',
            'suspended' => 'danger',
            'borrowed' => 'primary',
            'returned' => 'success',
            'overdue' => 'danger',
            'paid' => 'success',
            'unpaid' => 'danger',
            'partial' => 'warning',
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
        ];

        return $badges[strtolower($status)] ?? 'secondary';
    }
}

if (!function_exists('get_status_text')) {
    /**
     * Get Indonesian text for status
     *
     * @param string $status Status string
     * @return string Indonesian status text
     * 
     * @example
     * get_status_text('borrowed'); // Output: 'Dipinjam'
     * get_status_text('overdue');  // Output: 'Terlambat'
     */
    function get_status_text($status)
    {
        $texts = [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'suspended' => 'Suspended',
            'borrowed' => 'Dipinjam',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat',
            'paid' => 'Lunas',
            'unpaid' => 'Belum Lunas',
            'partial' => 'Sebagian',
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
        ];

        return $texts[strtolower($status)] ?? ucfirst($status);
    }
}

// =====================================================
// SESSION HELPERS
// =====================================================

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     *
     * @return bool True if user is logged in, false otherwise
     * 
     * @example
     * if (is_logged_in()) {
     *     echo "Welcome!";
     * }
     */
    function is_logged_in()
    {
        return session()->has('isLoggedIn') && session()->get('isLoggedIn') === true;
    }
}

if (!function_exists('get_user')) {
    /**
     * Get current logged in user data
     *
     * @param string|null $key Specific key to get from user data
     * @return mixed User data array or specific value if key provided
     * 
     * @example
     * $user = get_user();           // Get all user data
     * $name = get_user('name');     // Get specific field
     */
    function get_user($key = null)
    {
        $user = session()->get('user');
        
        if ($key !== null && is_array($user)) {
            return $user[$key] ?? null;
        }
        
        return $user;
    }
}

if (!function_exists('get_member')) {
    /**
     * Get current member data
     *
     * @param string|null $key Specific key to get from member data
     * @return mixed Member data array or specific value if key provided
     * 
     * @example
     * $member = get_member();              // Get all member data
     * $memberCode = get_member('member_code'); // Get specific field
     */
    function get_member($key = null)
    {
        $member = session()->get('member');
        
        if ($key !== null && is_array($member)) {
            return $member[$key] ?? null;
        }
        
        return $member;
    }
}

if (!function_exists('get_token')) {
    /**
     * Get authentication token
     *
     * @return string|null Authentication token or null if not logged in
     * 
     * @example
     * $token = get_token();
     * $response = api_request('GET', $url, [], $token);
     */
    function get_token()
    {
        return session()->get('token');
    }
}

if (!function_exists('set_flash')) {
    /**
     * Set flash message
     *
     * @param string $type Message type (success, error, warning, info)
     * @param string $message Message content
     * @return void
     * 
     * @example
     * set_flash('success', 'Data berhasil disimpan!');
     * set_flash('error', 'Terjadi kesalahan!');
     */
    function set_flash($type, $message)
    {
        session()->setFlashdata($type, $message);
    }
}

// =====================================================
// UTILITY HELPERS
// =====================================================

if (!function_exists('truncate_text')) {
    /**
     * Truncate text to specified length
     *
     * @param string $text Text to truncate
     * @param int $length Maximum length (default: 100)
     * @param string $suffix Suffix to append (default: '...')
     * @return string Truncated text
     * 
     * @example
     * truncate_text('Long text here...', 10); // Output: 'Long te...'
     */
    function truncate_text($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('generate_random_string')) {
    /**
     * Generate random string
     *
     * @param int $length Length of string (default: 10)
     * @param string $type Type: 'alnum', 'alpha', 'numeric' (default: 'alnum')
     * @return string Random string
     * 
     * @example
     * generate_random_string(8, 'numeric'); // Output: '12345678'
     */
    function generate_random_string($length = 10, $type = 'alnum')
    {
        helper('text');
        return random_string($type, $length);
    }
}

if (!function_exists('calculate_days_between')) {
    /**
     * Calculate days between two dates
     *
     * @param string $date1 First date
     * @param string $date2 Second date (default: today)
     * @return int Number of days
     * 
     * @example
     * calculate_days_between('2024-12-01', '2024-12-18'); // Output: 17
     */
    function calculate_days_between($date1, $date2 = null)
    {
        if ($date2 === null) {
            $date2 = date('Y-m-d');
        }
        
        $datetime1 = new \DateTime($date1);
        $datetime2 = new \DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        
        return abs($interval->days);
    }
}

if (!function_exists('is_overdue')) {
    /**
     * Check if date is overdue (past due date)
     *
     * @param string $dueDate Due date
     * @param string $compareDate Date to compare (default: today)
     * @return bool True if overdue, false otherwise
     * 
     * @example
     * is_overdue('2024-12-01'); // Output: true (if today is after 2024-12-01)
     */
    function is_overdue($dueDate, $compareDate = null)
    {
        if ($compareDate === null) {
            $compareDate = date('Y-m-d');
        }
        
        return strtotime($dueDate) < strtotime($compareDate);
    }
}

if (!function_exists('format_file_size')) {
    /**
     * Format file size to human readable format
     *
     * @param int $bytes File size in bytes
     * @param int $precision Decimal precision (default: 2)
     * @return string Formatted file size
     * 
     * @example
     * format_file_size(1024);      // Output: '1.00 KB'
     * format_file_size(1048576);   // Output: '1.00 MB'
     */
    function format_file_size($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

// =====================================================
// VALIDATION HELPERS
// =====================================================

if (!function_exists('is_valid_email')) {
    /**
     * Validate email address
     *
     * @param string $email Email to validate
     * @return bool True if valid, false otherwise
     * 
     * @example
     * is_valid_email('test@example.com'); // Output: true
     */
    function is_valid_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize user input
     *
     * @param string $input Input to sanitize
     * @return string Sanitized input
     * 
     * @example
     * sanitize_input('<script>alert("XSS")</script>'); // Output: clean text
     */
    function sanitize_input($input)
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}

// End of api_helper.php