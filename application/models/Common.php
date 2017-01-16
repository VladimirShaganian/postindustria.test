<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Common
 *
 * Contain main help methods
 */
class Common extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Renders site header
     */
    public function get_header() 
    {
        $this->load->view('_templates/header');
    }

    /**
     *  Renders site footer
     */
    public function get_footer()
    {
        $this->load->view('_templates/footer');
    }

    /**
     * @param int $limit
     * @return array
     *
     * Generates list of dates for dropdown
     */
    public function get_range_dates($limit = 6)
    {
        $carbon = Carbon\Carbon::now();
        $dates = [];
        for ($i = 1; $i <= $limit; $i++) {
            $m = $carbon->subMonth()->month;
            $y = $carbon->year;
            $dates[] = "$y-$m-01";
        }

        return $dates;

    }

    /**
     * Generates random data for all users
     *
     * @return bool
     */
    public function generate_data()
    {

        $faker = Faker\Factory::create();
        $this->db->truncate('transfer_logs');
        $users = $this->db->get('users')->result_array();
        $records = $faker->numberBetween(50, 500);
        $user_transfers = [];

        if (!empty($users)) {
            foreach ($users as $user) {
                $month_sum = $this->get_random_parts(6, $records);
                $carbon = Carbon\Carbon::now();
                foreach ($month_sum as $k => $v) {
                    $m = $carbon->subMonth()->month;
                    $y = $carbon->year;
                    $date = '';
                    while ($v >= 2) {
                        $date = $faker->unique()->dateTimeBetween("$y-$m-01 00:00:00", "$y-$m-31 23:59:59");
                        $date = date_format($date, 'Y-m-d');
                        $transfers_count = $faker->numberBetween(2, $v);
                        for ($i = 0; $i < $transfers_count; $i++ ) {
                            $date_create = $faker->unique()->dateTimeBetween("$date 00:00:00", "$date 23:59:59");
                            $date_create = date_format($date_create, "Y-m-d H:i:s");
                            $transfered = $this->generate_random_number($faker->numberBetween(3, 12));
                            $user_transfers[] = [
                                'user_id' => $user['id'],
                                'resource' => $faker->url,
                                'date_create' => $date_create,
                                'transfered' => $transfered,
                            ];
                        }
                        $v -= $transfers_count;
                    }
                    if ($v > 0) {
                        $date_create = $faker->unique()->dateTimeBetween("$date 00:00:00", "$date 23:59:59");
                        $date_create = date_format($date_create, "Y-m-d H:i:s");
                        $transfered = $this->generate_random_number($faker->numberBetween(3, 12));
                        $user_transfers[] = [
                            'user_id' => $user['id'],
                            'resource' => $faker->url,
                            'date_create' => $date_create,
                            'transfered' => $transfered
                        ];
                    }
                }
            }
            if ($this->db->insert_batch('transfer_logs', $user_transfers)) {
                return true;
            };
        }
        return false;
    }


    /**
     * Creates array of random summands 
     *
     * @param $parts_num
     * @param $value
     * @return array
     */
    public function get_random_parts($parts_num, $value)
    {
        $result = [];
        $sum = 0;
        $part = intval($value / $parts_num);
        $limit = intval(round($part * 0.2));

        for ($i = 1 ; $i < $parts_num; $i++) {
            $result[$i] = rand($part - $limit, $part + $limit);
            $sum += $result[$i];
        }

        $result[$parts_num] = $value - $sum;

        return $result;
    }

    /**
     * Generates bigint number with 500 in min
     * 
     * @param $length
     * @return int|string
     */
    public function generate_random_number($length) {
        $faker = Faker\Factory::create();
        $randomNumber = $faker->numberBetween(1, 9);
        for ($i = 1; $i < $length; $i++) {
            $randomNumber .= $i == $length - 1 ? $faker->randomElement([0,5,6,7,8,9]) : $faker->numberBetween(0, 9);
        }
        $randomNumber .= '00';
        return $randomNumber;
    }

    /**
     * Get data for reports
     * 
     * @param null $id
     * @return mixed
     */
    public function get_report($id = null)
    {
        $where_date = '';
        if (isset($_GET['date']) && !empty($_GET['date'])) {
            $date = $_GET['date'];
            $where_date =  " AND MONTH(transfer_logs.date_create) = MONTH('$date') ";
        }

        $sql = "
        SELECT TRUNCATE (SUM(transfered) / 1000000000000, 0) AS transfered_data, companies. NAME AS company_name, companies.quota
        FROM transfer_logs
        JOIN users ON users.id = transfer_logs.user_id
        JOIN companies ON companies.id = users.company_id
        WHERE (SELECT TRUNCATE (SUM(transfered) / 1000000000000, 0) FROM transfer_logs t
                JOIN users u ON u.id = t.user_id
                JOIN companies c ON c.id = u.company_id
                WHERE c.id = companies.id) 
                > quota $where_date
        GROUP BY companies.id ORDER BY transfered_data DESC
        ";
        
        $result = $this->db->query($sql)->result_array();
        
        return $result;

    }



}