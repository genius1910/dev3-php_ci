<?php

class Site extends Controller
{
    public $canSeeMargins, $canSeeOMR;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('site_model');
        $this->load->library('session');
        $this->canSeeMargins = canSeeMargins();
        $this->canSeeOMR = canSeeOMR();
    }

    public function index()
    {
        if ($this->site_model->is_logged_in() !== false) {
            redirect('dashboard');
        }
        if (strstr(current_url(), 'login.php') == false) {
            redirect('login.php');
        }
        $data['main_content'] = '';
        $this->load->view('site/login', $data);
    }

    public function password()
    {
        if ($this->site_model->is_logged_in() !== false) {
            redirect('dashboard');
        }

        $email = $this->input->post('email');
        $data["status"] = $status;
        $data['main_content'] = '';
        $this->load->view('site/password_template', $data);
    }

    public function loginForm()
    {
        $query = $this->site_model->get_user_login($this->input->post('user_name'), $this->input->post('userPass'));
        if (count($query) >= 1) // if the user's credentials validated...
        {
            if ("B" == $query->usertype) {
                $selectedUser = array();
                $branchname = $this->site_model->getBranch($query->branch);
                $branchSession = array(
                    "branchno" => $query->branch,
                    "name" => $branchname
                );
            } else {
                if ("R" == $query->usertype) {
                    $branchSession = array();
                    $selectedUser = array(
                        "userid" => $query->userid,
                        "firstname" => $query->firstname,
                        "surname" => $query->surname
                    );
                } else {
                    $branchSession = array();
                    $selectedUser = array();
                }
            }

            $data = array(
                'username' => $query->firstname,
                'userid' => $query->userid,
                'usertype' => $query->usertype,
                // Adding new key to identify the user type
                'is_logged_in' => true,
                'selectedBranch' => $branchSession,
                'selectedUser' => $selectedUser,
                'defaultmodule' => $query->defaultmodule,
                'licencetype' => $query->licencetype,
                'hiddenuser' => $query->hiddenuser
            );
            $this->session->set_userdata($data);
            $this->load->model('users_model');
            $description = "User login";
            $this->users_model->savelog($description, $query->usertype);
            $data['status'] = "Success";
            if($query->defaultmodule == 'S')
                $data['url'] = 'dashboard';
            else
                $data['url'] = 'inventory';
            /*if(isset($_COOKIE[$this->config->item('site_name').'_'.$this->session->userdata('userid').'_last_visited'])) {
                $data['url']=$_COOKIE[$this->config->item('site_name').'_'.$this->session->userdata('userid').'_last_visited'];
            }*/
        } else // incorrect username or password
        {
            $result = $this->site_model->get_active($this->input->post('user_name'), $this->input->post('userPass'));
            if (count($result) > 0 && 0 == $result->active) {
                $data['msg'] = "This user is inactive and cannot login";
            } else {
                $data['msg'] = "Invalid user";
            }

            $data['status'] = "failed";
        }
        echo json_encode($data);
    }

    public function setPassword()
    {
        $query = $this->site_model->get_user($this->input->post('email'));
        //var_dump($query);
        if (count($query) >= 1) { // if the user's credentials validated...
            $this->load->library('email', array('mailtype' => 'html'));

            $encrypt = md5(90 * 13 + intval($query['userid']));
            $message = '<p>Hi ' . $query['firstname'] . ',</p><p>Click the link below to set your MI-DAS password</p><p>' . base_url() . 'site/resetpassword/' . $encrypt . ' </p>';

            $this->email->from('kieran@kk-cs.co.uk', 'Kieran Kelly');
            $this->email->to($this->input->post('email'));

            $this->email->subject('Set Your MI-DAS Password');
            $this->email->message($message);

            $this->email->send();

            $data['status'] = "success";
            $data['msg'] = 'Please check your mail';
            $message = "Please check your email" . $this->input->post('email') . " to set a login password.";
        } else { // incorrect username or password
            $data['status'] = "danger";
            $data['msg'] = "Invalid email";
            $message = "The email is not stored in the database.";
        }
        //echo"here".$data['status'];exit;
        $this->session->set_flashdata('password_message', "<div class='alert alert-" . $data['status'] . "'>" . $message . "</div>");
        redirect("set-password");
    }

    public function forgotPassword()
    {
        $query = $this->site_model->get_user($this->input->post('email'));
        //var_dump($query);
        if (count($query) >= 1) // if the user's credentials validated...
        {

            $this->load->library('email');

            $encrypt = md5(90 * 13 + intval($query['userid']));
            $message = 'Hi ' . $query['firstname'] . ',<br/> <br/>Click here to reset your MI-DAS password ' . base_url() . 'site/resetpassword/' . $encrypt;

            $this->email->from('kieran@kk-cs.co.uk', 'Kieran Kelly');
            $this->email->to($this->input->post('email'));

            $this->email->subject('Forget Your MI-DAS Password');
            $this->email->message($message);

            $this->email->send();

            $data['status'] = "Success";
            $data['msg'] = 'Please check your mail';
        } else // incorrect username or password
        {
            $data['status'] = "failed";
            $data['msg'] = "Invalid email";
        }
        echo json_encode($data);
    }

    public function resetpassword($encrypt = '')
    {
        if ($this->site_model->is_logged_in() !== false) {
            redirect('dashboard');
        }
        $data['user'] = $this->site_model->get_user_encrypt($encrypt);
        $data['encrypt'] = $encrypt;
        if ($data['user']['userid'] > 0) {
            $data['error'] = "";
        } else {
            $data['error'] = "Invalid encryption";
            $data['user']['userid'] = 0;
        }
        $data['main_content'] = '';
        $this->load->view('site/forgot_password', $data);
    }

    public function resetMyPass()
    {
        $status = $this->site_model->update_user_password($this->input->post('newPass'), $this->input->post('enc'));
        if ($status > 0) {
            $data['status'] = "Success";
            $data['url'] = base_url() . 'login.php';
        } else //
        {
            $data['status'] = "failed";
            $data['msg'] = "Invalid encryption";

        }
        //echo json_encode($data);
    }

    function logout()
    {
        $this->load->model('users_model');
        $description = "User logout";
        $this->users_model->savelog($description, $this->session->userdata('usertype'));
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('is_logged_in');
        $this->session->sess_destroy();
        redirect('/');
    }

    public function dashboard()
    {
        if ($this->site_model->is_logged_in() == false) {
            redirect('/');
        }

        $data["canSeeMargins"] = $this->canSeeMargins;
        $data["canSeeOMR"] = $this->canSeeOMR;

        $this->load->helper('cookie');

        if (isset($_COOKIE['salestodaydonutcharts'])) {
            $data['salestodaydonutcharts'] = get_cookie('salestodaydonutcharts', true);
        } else {
            $data['salestodaydonutcharts'] = 0;
        }

        if (isset($_COOKIE['outstandingordersdonutchart'])) {
            $data['outstandingordersdonutchart'] = get_cookie('outstandingordersdonutchart', true);
        } else {
            $data['outstandingordersdonutchart'] = 0;
        }

        if (isset($_COOKIE['threeyearsaleschart'])) {
            $data['threeyearsaleschart'] = get_cookie('threeyearsaleschart', true);
        } else {
            $data['threeyearsaleschart'] = 0;
        }

        $data['year0'] = date("Y");
        $data['year1'] = $data['year0'] - 1;
        $data['year2'] = $data['year0'] - 2;
        $data['year3'] = $data['year0'] - 3;
        $data['yearstartmonth'] = $this->site_model->getYearStartMonth();

        $heldinomr = $this->site_model->heldInomr($G_level, $G_todaysdate, $repclause, $G_branchno);

        $data["HeldInOMRCR"] = '0.00';
        $data["HeldInOMRSL"] = '0.00';

        foreach ($heldinomr as $hr)
        {
            if ($hr['identifier'] == "MIDASHELDOMRSL")
            {
                $data["HeldInOMRSL"] = $hr['actualvalue1'];
            }
            if ($hr['identifier'] == "MIDASHELDOMRCR")
            {
                $data["HeldInOMRCR"] = $hr['actualvalue1'];
            }
        }

        $waitingposting = $this->site_model->waitingposting($G_level, $G_todaysdate, $repclause, $G_branchno);

        $data["WaitingPostingCR"] = '0.00';
        $data["WaitingPostingSL"] = '0.00';

        foreach ($waitingposting as $wp)
        {
            if ($wp['identifier'] == "MIDASWAITSL")
            {
                $data["WaitingPostingSL"] = $wp['sum1'];
            }
            if ($wp['identifier'] == "MIDASWAITCR")
            {
                $data["WaitingPostingCR"] = $wp['sum1'];
            }
        }

        $postedOmr = $this->site_model->postedOmr($G_level, $G_todaysdate, $repclause, $G_branchno);

        $data["PostedCR"] = '0.00';
        $data["PostedSL"] = '0.00';

        foreach ($postedOmr as $Pr)
        {
            if ($Pr['identifier'] == "MIDASPOSTEDSL")
            {
                $data["PostedSL"] = $Pr['actualvalue1'];
            }
            if ($Pr['identifier'] == "MIDASPOSTEDCR")
            {
                $data["PostedCR"] = $Pr['actualvalue1'];
            }
        }

        $data['main_content'] = 'dashboard';
        $this->load->view('site/front_template', $data);
    }

    public function getSalesDailyReport()
    {
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));
        $data['lastsalesdate'] = $this->site_model->getMaxDate($data['userDetail']['repwhere']);

        $userType = $data['userType'] = $data['userDetail']['usertype'];

        $userId = 0;
        $branchNo = 0;
        $G_branchno = null;
        $G_todaysdate = date("Y/m/d");
        $data['curyearmonth'] = (date("Y") * 100) + date("m");

        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $G_userid = $this->session->userdata("userid");
        $row1 = $this->site_model->getSalesRepTarget($data['curyearmonth'], $G_userid, $G_branchno, $G_level);
        $data['G_MonthlySalesTarget'] = $row1['salesTarget'] = $row1['saletarget'];

        $row = $this->site_model->getSalesRepLastSales($data['lastsalesdate'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);
        $data['dailysales'] = $row['sales'];

        $date = $G_todaysdate;
        $data["$G_todaysdate"] = $G_todaysdate;
        $row = $this->site_model->workingDays($date);
        $data['totdays'] = $row['totdays']; // Total number of working days in the month

        $data['G_DailySalesTarget'] = 0;
        if ($data['totdays'] <> 0) {
            $data['G_DailySalesTarget'] = $data['G_MonthlySalesTarget'] / $data['totdays'];
        }
        if ($data['G_DailySalesTarget'] <> 0) {
            $data['dailysalespc'] = ($data['dailysales'] / $data['G_DailySalesTarget']) * 100;
        } else {
            $data['dailysalespc'] = 0;
        }

        $data['lastsalesdate'] = date('d/m/Y', strtotime($data['lastsalesdate']));
        $currency_symbol = $this->config->item("currency_symbol");
        $data['currency_symbol'] = $currency_symbol;

        echo json_encode($data);
    }

    public function getSalesDailyMargin()
    {
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));
        $data['lastsalesdate'] = $this->site_model->getMaxDate($data['userDetail']['repwhere']);

        $userType = $data['userType'] = $data['userDetail']['usertype'];

        $userId = 0;
        $branchNo = 0;
        $G_branchno = null;

        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }

        $G_userid = $this->session->userdata("userid");

        $row = $this->site_model->getSalesRepLastSales($data['lastsalesdate'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);
        $data['dailysales'] = $row['sales'];
        $data['dailycost'] = $row['costs'];

        $data['dailymargin'] = $data['dailysales'] - $data['dailycost'];
        if ($data['dailysales'] <> 0) {
            $data['dailymarginpc'] = ($data['dailymargin'] / $data['dailysales']) * 100;
        } else {
            $data['dailymarginpc'] = 0;
        }

        $data['lastsalesdate'] = date('d/m/Y', strtotime($data['lastsalesdate']));
        $currency_symbol = $this->config->item("currency_symbol");
        $data['currency_symbol'] = $currency_symbol;

        echo json_encode($data);
    }

    public function getSalesMontlyReport()
    {
        $data['thismonth'] = date("m");
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));
        $data['lastsalesdate'] = $this->site_model->getMaxDate($data['userDetail']['repwhere']);

        $userType = $data['userType'] = $data['userDetail']['usertype'];

        $userId = 0;
        $branchNo = 0;
        $G_branchno = null;
        $data['year3'] = date("Y") - 3;
        $data['curyearmonth'] = (date("Y") * 100) + date("m");

        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $G_userid = $this->session->userdata("userid");
        $row1 = $this->site_model->getSalesRepTarget($data['curyearmonth'], $G_userid, $G_branchno, $G_level);
        $data['G_MonthlySalesTarget'] = $row1['salesTarget'] = $row1['saletarget'];

        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();

        // Preload the year and month into an array so that we can make sure we load the sales against the correct row. Pad the month with leading 0 if needed. Had an example where
        // a rep started more recently that three years ago, and therefore there was less than 36 months. It was loading all these into the start of the array, rather than against the
        // appropriate row.

        $data['tmpyear'] = $data['year3']; //CR0001 $year3;
        $data['tmpmonth'] = 1; // CR0001 $thismonth + 1;

        for ($x = 0; $x < 48; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        // Get sales for the sales rep
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x < 48; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }
        // end getting targets of all years
        $data['monthlysales'] = $data['sales'][23 + $data['thismonth']]; // CR0001 $sales[35];
        $data['monthlycost'] = $data['costs'][23 + $data['thismonth']]; // CR0001 $costs[35];
        if ($data['G_MonthlySalesTarget'] != 0) {
            //echo $data['monthlysales'].'-'.$data['G_MonthlySalesTarget'] ;
            $data['monthlysalespc'] = ($data['monthlysales'] / $data['G_MonthlySalesTarget']) * 100;
        } else {
            $data['monthlysalespc'] = 0;
        }

        $data['lastsalesdate'] = date('d/m/Y', strtotime($data['lastsalesdate']));
        $currency_symbol = $this->config->item("currency_symbol");
        $data['currency_symbol'] = $currency_symbol;

        echo json_encode($data);
    }

    public function getSalesMontlyMargin(){
        $data['thismonth'] = date("m");
        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();
        $data['year0'] = date("Y");
        $data['year3'] = $data['year0'] - 3;
        // Preload the year and month into an array so that we can make sure we load the sales against the correct row. Pad the month with leading 0 if needed. Had an example where
        // a rep started more recently that three years ago, and therefore there was less than 36 months. It was loading all these into the start of the array, rather than against the
        // appropriate row.

        $data['tmpyear'] = $data['year3']; //CR0001 $year3;
        $data['tmpmonth'] = 1; // CR0001 $thismonth + 1;

        for ($x = 0; $x < 48; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        // Get sales for the sales rep
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x < 48; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }
        // end getting targets of all years
        $data['monthlysales'] = $data['sales'][23 + $data['thismonth']]; // CR0001 $sales[35];
        $data['monthlycost'] = $data['costs'][23 + $data['thismonth']]; // CR0001 $costs[35];
        $data['monthlymargin'] = $data['monthlysales'] - $data['monthlycost'];
        if ($data['monthlysales'] <> 0) {
            $data['monthlymarginpc'] = ($data['monthlymargin'] / $data['monthlysales']) * 100;
        } else {
            $data['monthlymarginpc'] = 0;
        }
        $currency_symbol = $this->config->item("currency_symbol");
        $data['currency_symbol'] = $currency_symbol;
        echo json_encode($data);
    }

    public function getTodayOrdersType(){
        $currency_symbol = $this->config->item("currency_symbol");
        $data['currency_symbol'] = $currency_symbol;
        $userId = 0;
        $branchNo = 0;
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
		$branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $repclause = $data["userDetail"]["repclause"];
        $G_todaysdate = date("Y/m/d");
        $G_branchno = null;
        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $data['todayOrdersType'] = $this->site_model->todayOrders($G_level, $G_todaysdate, $repclause, $G_branchno);

        echo json_encode($data);
    }

    public function getOrdersByStatusTable(){
        $currency_symbol = $this->config->item("currency_symbol");
        $data['currency_symbol'] = $currency_symbol;
        $userId = 0;
        $branchNo = 0;
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
		$branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $repclause = $data["userDetail"]["repclause"];
        $G_todaysdate = date("Y/m/d");
        $G_branchno = null;
        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $data['todayOrdersStatus'] = $this->site_model->todayOrdersStatus($G_level, $G_todaysdate, $repclause, $G_branchno);
        echo json_encode($data);
    }

    public function getOutByStatus(){
        $currency_symbol = $this->config->item("currency_symbol");
        $data['currency_symbol'] = $currency_symbol;
        $userId = 0;
        $branchNo = 0;
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
		$branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $repclause = $data["userDetail"]["repclause"];
        $G_todaysdate = date("Y/m/d");
        $G_branchno = null;
        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $data['outOrders'] = $this->site_model->outStandOrders($G_level, $G_todaysdate, $repclause, $G_branchno);
        echo json_encode($data);
    }

    public function getTwoYearsTargetTable(){
        $userId = 0;
        $branchNo = 0;
        $data['year0'] = date("Y");
        $data['year1'] = date("Y") - 1;
        $data['year2'] = date("Y") - 2;
        $data['year3'] = $data['year0'] - 3;
        $data['yearstartmonth'] = $this->site_model->getYearStartMonth();
        $start_month_delta = $data['yearstartmonth'] <= date('m') ? 11 + $data['yearstartmonth'] : $data['yearstartmonth'] - 1;
        
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
		$branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $G_branchno = null;
        $G_userid = $this->session->userdata("userid");
        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_userId = $userId;
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $targetUserId = $G_userid;

        // This is a potential bug fix, for some reason $userKpi is returning an empty array and therefore not working with the following function
        $userDetailAsKpi = array($data['userDetail']);
        $data = $this->site_model->GetKpiDataForTwoYearVsTargetChart($userDetailAsKpi, $data, $G_level);

        // Initialise sales array

        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();

        // Preload the year and month into an array so that we can make sure we load the sales against the correct row. Pad the month with leading 0 if needed. Had an example where
        // a rep started more recently that three years ago, and therefore there was less than 36 months. It was loading all these into the start of the array, rather than against the
        // appropriate row.

        $data['tmpyear'] = $data['year3']; //CR0001 $year3;
        $data['tmpmonth'] = 1; // CR0001 $thismonth + 1;

        for ($x = 0; $x < 48; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        // Get sales for the sales rep
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x < 48; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }

        $data['salesTargetForLastThreeYear'] = $this->site_model->getSalesTargetForLastThreeYear($G_level, $data['yearmonth'], $data['sales'], $targetUserId, $G_branchno);
        $data['targetDataForCurrentYear'] = $this->site_model->GetTargetDataForCurrentYear($data['salesTargetForLastThreeYear']);
        $data['cumulativeTargetDataForCurrentYear'] = $this->site_model->GetCumulativeTargetDataForCurrentYear($data['salesTargetForLastThreeYear']);

        $data['year0data'] = $this->site_model->GetYearData($data['sales'], 24 + $start_month_delta, 35 + $start_month_delta);
        $data["year0total"] = $this->site_model->GetYearTotal($data['sales'], 24 + $start_month_delta, 35 + $start_month_delta);
        $data["year0table"] = $this->site_model->GetYearTable($data['sales'], $data["year0total"], 24 + $start_month_delta, 35 + $start_month_delta);

        $data['year1data'] = $this->site_model->GetYearData($data['sales'], 12 + $start_month_delta, 23 + $start_month_delta);
        $data["year1total"] = $this->site_model->GetYearTotal($data['sales'], 12 + $start_month_delta, 23 + $start_month_delta);
        $data["year1table"] = $this->site_model->GetYearTable($data['sales'], $data["year1total"], 12 + $start_month_delta, 23 + $start_month_delta);

        $data['year2data'] = $this->site_model->GetYearData($data['sales'], $start_month_delta, 11 + $start_month_delta);
        $data["year2total"] = $this->site_model->GetYearTotal($data['sales'], $start_month_delta, 11 + $start_month_delta);
        $data["year2table"] = $this->site_model->GetYearTable($data['sales'], $data["year2total"], $start_month_delta, 11 + $start_month_delta);

        $data['year0ChartValues'] = $data['year0data'];
        $data['year1ChartValues'] = $data['year1data'];
        $data['year2ChartValues'] = $data['year2data'];
        $data['cumulativeYear0ChartValues'] = $this->site_model->GetCumulativeYearData($data['sales'], 24 + $start_month_delta, 35 + $start_month_delta);
        $data['cumulativeYear1ChartValues'] = $this->site_model->GetCumulativeYearData($data['sales'], 12 + $start_month_delta, 23 + $start_month_delta);
        $data['cumulativeYear2ChartValues'] = $this->site_model->GetCumulativeYearData($data['sales'], $start_month_delta, 11 + $start_month_delta);

        $data['yearstartmonth'] = $this->site_model->getYearStartMonth();
        echo json_encode($data);
    }

    public function getPacSaleVsTarget(){
        $userId = 0;
        $branchNo = 0;
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
		$branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $repclause = $data["userDetail"]["repclause"];
        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $targetUserId = $this->session->userdata("userid");
        $data['pac1salestarget'] = $this->site_model->getPac1SalesTargetDashboard($G_level, $targetUserId, $branchNo, $repclause);
        $data['getSalesTotalMonthWise'] = $this->site_model->getSalesTotalMonthWise($G_level, $targetUserId, $branchNo, $repclause);
        echo json_encode($data);
    }

    public function getQuotationsXPac1(){
        $selectedUserDetails = array('repwhere' => "");
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];

            $selectedUserDetails = $this->site_model->getUserDetails($userId);
        }
        $data['currentMonthPac1QuoteConversions'] = $this->site_model->getPac1QuoteConversionForCurrentMonth($selectedUserDetails['repwhere']);
        echo json_encode($data);
    }

    public function getSalesPipeline(){
        $selectedUserDetails = array('repwhere' => "");
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];

            $selectedUserDetails = $this->site_model->getUserDetails($userId);
        }
        $data['salesPipelineStages'] = $this->site_model->getSalesPipelineStages($selectedUserDetails['repwhere']);
        echo json_encode($data);
    }

    public function getSalesMonthChart(){
        $G_todaysdate = date("Y/m/d");
        $data['thismonth'] = date("m");
        $daysinmonth = date("t", strtotime($G_todaysdate));
        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();

        $data['yearstartmonth'] = $this->site_model->getYearStartMonth();
        
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
		$branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $G_branchno = null;
        $G_userid = $this->session->userdata("userid");
        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        
        // Preload the year and month into an array so that we can make sure we load the sales against the correct row. Pad the month with leading 0 if needed. Had an example where
        // a rep started more recently that three years ago, and therefore there was less than 36 months. It was loading all these into the start of the array, rather than against the
        // appropriate row.
        $data['year0'] = date("Y");
        $data['year1'] = $data['year0'] - 1;
        $data['year2'] = $data['year0'] - 2;
        $data['year3'] = $data['year0'] - 3;

        $data['thismonth'] = date("m");
        $data['curyearmonth'] = ($data['year0'] * 100) + $data['thismonth']; // e.g. 201507

        $data['tmpyear'] = $data['year3']; //CR0001 $year3;
        $data['tmpmonth'] = 1; // CR0001 $thismonth + 1;

        for ($x = 0; $x < 48; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        // Get sales for the sales rep
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x < 48; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }
        $data['monthlysales'] = $data['sales'][23 + $data['thismonth']];
        $data["projmonthsalespc"] = '';
        $kworkingDays = $this->site_model->workingDays($G_todaysdate);
        $data["dayno"] = $kworkingDays['dayno'];
        $data["totdays"] = $kworkingDays['totdays'];
        if ($data["dayno"] <> 0) {
            $data["projdaysales"] = ($data["monthlysales"] / $data["dayno"]);
            $data["projmonthsales"] = $data["projdaysales"] * $data["totdays"]; // Extrapolate projected sales
            if ($data["G_MonthlySalesTarget"] <> 0) {
                $data["projmonthsalespc"] = ($data["projmonthsales"] / $data["G_MonthlySalesTarget"]) * 100;
            }
        }

        // Build up the cumulative target and projected arrays

        $ProjectedSalesMonthGraphTarget = "[";
        $ProjectedSalesMonthGraphProjected = "[";
        $ProjectedSalesMonthGraphLabel = "[";
        $data["daysinmonth"] = $daysinmonth;
        for ($x = 1; $x <= $daysinmonth; $x++) {
            $cumulativetarget[$x] = ($data["G_MonthlySalesTarget"] / $data["daysinmonth"]) * $x;
            $cumulativeprojected[$x] = ($data["projmonthsales"] / $data["daysinmonth"]) * $x;

            $ProjectedSalesMonthGraphTarget .= number_format($cumulativetarget[$x], 0, '.', '');
            $ProjectedSalesMonthGraphProjected .= number_format($cumulativeprojected[$x], 0, '.', '');

            // Only putting the first and last day number in the label as its too busy with all the days

            if ($x == 1 or $x == $daysinmonth) {
                $ProjectedSalesMonthGraphLabel .= "'$x'";
            } else {
                $ProjectedSalesMonthGraphLabel .= "' '";
            }
            if ($x != $daysinmonth) {
                $ProjectedSalesMonthGraphTarget .= ",";
                $ProjectedSalesMonthGraphProjected .= ",";
                $ProjectedSalesMonthGraphLabel .= ",";
            }
        }

        $ProjectedSalesMonthGraphTarget .= "]";
        $ProjectedSalesMonthGraphProjected .= "]";
        $ProjectedSalesMonthGraphLabel .= ",' ']";
        //$ProjectedSalesMonthGraphLabel .= ",' ']";

        $data["ProjectedSalesMonthGraphTarget"] = $ProjectedSalesMonthGraphTarget;
        $data["ProjectedSalesMonthGraphProjected"] = $ProjectedSalesMonthGraphProjected;
        $data["ProjectedSalesMonthGraphLabel"] = $ProjectedSalesMonthGraphLabel;

        // Sales month to date, divided by the number of working days so far, multipled by the total number of working days in the month to plot where sales will be if they continue like this
        
        $userId = 0;
        $branchNo = 0;
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
		$branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $repclause = $data["userDetail"]["repclause"];
        $G_todaysdate = date("Y/m/d");
        $G_branchno = null;
        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $cumday = $this->site_model->cumday($G_level, $G_todaysdate, $repclause, $G_branchno);
        //print_r($cumday[0]["SUM(day01sales)"]);

        $cumday01sales = number_format($cumday[0]["SUM(day01sales)"], 0, '.', '');
        $cumday02sales = number_format($cumday[0]["SUM(day02sales)"] + $cumday01sales, 0, '.', '');
        $cumday03sales = number_format($cumday[0]["SUM(day03sales)"] + $cumday02sales, 0, '.', '');
        $cumday04sales = number_format($cumday[0]["SUM(day04sales)"] + $cumday03sales, 0, '.', '');
        $cumday05sales = number_format($cumday[0]["SUM(day05sales)"] + $cumday04sales, 0, '.', '');
        $cumday06sales = number_format($cumday[0]["SUM(day06sales)"] + $cumday05sales, 0, '.', '');
        $cumday07sales = number_format($cumday[0]["SUM(day07sales)"] + $cumday06sales, 0, '.', '');
        $cumday08sales = number_format($cumday[0]["SUM(day08sales)"] + $cumday07sales, 0, '.', '');
        $cumday09sales = number_format($cumday[0]["SUM(day09sales)"] + $cumday08sales, 0, '.', '');
        $cumday10sales = number_format($cumday[0]["SUM(day10sales)"] + $cumday09sales, 0, '.', '');
        $cumday11sales = number_format($cumday[0]["SUM(day11sales)"] + $cumday10sales, 0, '.', '');
        $cumday12sales = number_format($cumday[0]["SUM(day12sales)"] + $cumday11sales, 0, '.', '');
        $cumday13sales = number_format($cumday[0]["SUM(day13sales)"] + $cumday12sales, 0, '.', '');
        $cumday14sales = number_format($cumday[0]["SUM(day14sales)"] + $cumday13sales, 0, '.', '');
        $cumday15sales = number_format($cumday[0]["SUM(day15sales)"] + $cumday14sales, 0, '.', '');
        $cumday16sales = number_format($cumday[0]["SUM(day16sales)"] + $cumday15sales, 0, '.', '');
        $cumday17sales = number_format($cumday[0]["SUM(day17sales)"] + $cumday16sales, 0, '.', '');
        $cumday18sales = number_format($cumday[0]["SUM(day18sales)"] + $cumday17sales, 0, '.', '');
        $cumday19sales = number_format($cumday[0]["SUM(day19sales)"] + $cumday18sales, 0, '.', '');
        $cumday20sales = number_format($cumday[0]["SUM(day20sales)"] + $cumday19sales, 0, '.', '');
        $cumday21sales = number_format($cumday[0]["SUM(day21sales)"] + $cumday20sales, 0, '.', '');
        $cumday22sales = number_format($cumday[0]["SUM(day22sales)"] + $cumday21sales, 0, '.', '');
        $cumday23sales = number_format($cumday[0]["SUM(day23sales)"] + $cumday22sales, 0, '.', '');
        $cumday24sales = number_format($cumday[0]["SUM(day24sales)"] + $cumday23sales, 0, '.', '');
        $cumday25sales = number_format($cumday[0]["SUM(day25sales)"] + $cumday24sales, 0, '.', '');
        $cumday26sales = number_format($cumday[0]["SUM(day26sales)"] + $cumday25sales, 0, '.', '');
        $cumday27sales = number_format($cumday[0]["SUM(day27sales)"] + $cumday26sales, 0, '.', '');
        $cumday28sales = number_format($cumday[0]["SUM(day28sales)"] + $cumday27sales, 0, '.', '');
        $cumday29sales = number_format($cumday[0]["SUM(day29sales)"] + $cumday28sales, 0, '.', '');
        $cumday30sales = number_format($cumday[0]["SUM(day30sales)"] + $cumday29sales, 0, '.', '');
        $cumday31sales = number_format($cumday[0]["SUM(day31sales)"] + $cumday30sales, 0, '.', '');

        //------------------------//End Posted-----------------------------------------------------------------------------------------------------

        $data["ProjectedSalesMonthGraphActual"] = "[$cumday01sales,$cumday02sales,$cumday03sales,$cumday04sales,$cumday05sales,$cumday06sales,$cumday07sales,$cumday08sales,$cumday09sales,$cumday10sales,
            $cumday11sales,$cumday12sales,$cumday13sales,$cumday14sales,$cumday15sales,$cumday16sales,$cumday17sales,$cumday18sales,$cumday19sales,$cumday20sales,
            $cumday21sales,$cumday22sales,$cumday23sales,$cumday24sales,$cumday25sales,$cumday26sales,$cumday27sales,$cumday28sales,$cumday29sales,$cumday30sales,
            $cumday31sales]";

        echo json_encode($data);
    }

    public function getSalesYearChart(){

        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();
        $data['year0'] = date("Y");
        $data['year1'] = $data['year0'] - 1;
        $data['year2'] = $data['year0'] - 2;
        $data['year3'] = $data['year0'] - 3;

        $data['thismonth'] = date("m");
        $data['curyearmonth'] = ($data['year0'] * 100) + $data['thismonth']; // e.g. 201507

        $data['tmpyear'] = $data['year3']; //CR0001 $year3;
        $data['tmpmonth'] = 1; // CR0001 $thismonth + 1;

        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));
        

        $data["projyearsalespc"] = "";

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
		    $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $G_branchno = null;
        $G_userid = $this->session->userdata("userid");
        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_userId = $userId;
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $targetUserId = $G_userid;
        $targetDataYear = $this->site_model->getYearTargetData($targetUserId, $G_branchno, $G_level);
        $data["G_YearlySalesTarget"] = $targetDataYear['saletarget'];

        for ($x = 0; $x < 48; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        // Get sales for the sales rep
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x < 48; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }
        

        $data['yearstartmonth'] = $this->site_model->getYearStartMonth();
        $start_month_delta = $data['yearstartmonth'] <= date('m') ? 11 + $data['yearstartmonth'] : $data['yearstartmonth'] - 1;

        $cummth01sales = number_format($data["sales"][24 + $start_month_delta], 0, '.', '');
        $cummth02sales = number_format($cummth01sales + $data["sales"][25 + $start_month_delta], 0, '.', '');
        $cummth03sales = number_format($cummth02sales + $data["sales"][26 + $start_month_delta], 0, '.', '');
        $cummth04sales = number_format($cummth03sales + $data["sales"][27 + $start_month_delta], 0, '.', '');
        $cummth05sales = number_format($cummth04sales + $data["sales"][28 + $start_month_delta], 0, '.', '');
        $cummth06sales = number_format($cummth05sales + $data["sales"][29 + $start_month_delta], 0, '.', '');
        $cummth07sales = number_format($cummth06sales + $data["sales"][30 + $start_month_delta], 0, '.', '');
        $cummth08sales = number_format($cummth07sales + $data["sales"][31 + $start_month_delta], 0, '.', '');
        $cummth09sales = number_format($cummth08sales + $data["sales"][32 + $start_month_delta], 0, '.', '');
        $cummth10sales = number_format($cummth09sales + $data["sales"][33 + $start_month_delta], 0, '.', '');
        $cummth11sales = number_format($cummth10sales + $data["sales"][34 + $start_month_delta], 0, '.', '');
        $cummth12sales = number_format($cummth11sales + $data["sales"][35 + $start_month_delta], 0, '.', '');

        // Something like this: sales [24070,36053,45000,53187,64540,64540,64540,75130,75130,75130, 75130,75130]
        $data["ProjectedSalesYearGraphActual"] = "[$cummth01sales,$cummth02sales,$cummth03sales,$cummth04sales,$cummth05sales,$cummth06sales,
                                                $cummth07sales,$cummth08sales,$cummth09sales,$cummth10sales,$cummth11sales,$cummth12sales]";

        // Build up the cumulative target and projected arrays

        $ProjectedSalesYearGraphTarget = "[";
        $ProjectedSalesYearGraphProjected = "[";

        for ($x = 1; $x <= 12; $x++) {
            $cumulativeprojected[$x] = ($data["projyearsales"] / 12) * $x;

            $ProjectedSalesYearGraphTarget .= number_format(($data["G_YearlySalesTarget"] / 12) * $x, 0, '.', '');
            $ProjectedSalesYearGraphProjected .= number_format(($data["projyearsales"] / 12) * $x, 0, '.', '');

            if ($x != 12) {
                $ProjectedSalesYearGraphTarget .= ",";
                $ProjectedSalesYearGraphProjected .= ",";
            }
        }

        $ProjectedSalesYearGraphTarget .= "]";
        $ProjectedSalesYearGraphProjected .= "]";


        $data["ProjectedSalesYearGraphTarget"] = $ProjectedSalesYearGraphTarget;
        $data["ProjectedSalesYearGraphProjected"] = $ProjectedSalesYearGraphProjected;

        echo json_encode($data);
    }

    public function OrderFulfillSameDay(){
        $OrdersFulfilledGraph = "[";
        $OrdersFulfilledGraphLabel = "[";
        $y = 0;
        $userId = 0;
        $branchNo = 0;
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));
        $G_todaysdate = date("Y/m/d");
        $userType = $data['userType'] = $data['userDetail']['usertype'];
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
		$branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $repclause = $data["userDetail"]["repclause"];
        $G_branchno = null;
        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        $lastthirty = $this->site_model->lastthirty($G_level, $repclause, $G_branchno);
        foreach ($lastthirty as $lasth) {

            $fulfilleddate = $lasth["date"];
            $fulfilledlines = $lasth["sum1"];
            $totallines = $lasth["sum2"];

            if ($totallines <> 0) {
                $percentage = ($fulfilledlines / $totallines) * 100;
            }

            if ($y <> 0) {
                $OrdersFulfilledGraph .= ",";
                $OrdersFulfilledGraphLabel .= ",";
            }

            $OrdersFulfilledGraph .= number_format($percentage, 2);

            // Label will be day/month like 07/1204020

            $tmp_month = date("m", strtotime($fulfilleddate));
            $tmp_day = date("d", strtotime($fulfilleddate));
            $tmp_daymonth = $tmp_day . "/" . $tmp_month;

            $OrdersFulfilledGraphLabel .= "'$tmp_daymonth'";
            $y++;
        }
        $OrdersFulfilledGraph .= "]";
        $OrdersFulfilledGraphLabel .= "]";

        $data["OrdersFulfilledGraph"] = $OrdersFulfilledGraph;
        $data["OrdersFulfilledGraphLabel"] = $OrdersFulfilledGraphLabel;

        $todayOrders = $this->site_model->todayOrders($G_level, $G_todaysdate, $repclause, $G_branchno);

        $BIColour = "#3c8dbc"; // Book Ins  		Light Blue
        $BOColour = "#f39c12"; // Book Outs  		Yellow
        $BTColour = "#001f3f"; // Branch Transfers	Navy
        $CRColour = "#dd4b39"; // Credits  		Red
        $DNColour = "#39cccc"; // Debit Notes  	Teal
        $QTColour = "#00c0ef"; // Quotations  		Aqua
        $SLColour = "#00a65a"; // Orders  			Green
        $WOColour = "#d2d6de"; // Works Orders  	Gray
        $RWColour = "#f44295";
        $TCColour = "#7a1919";
        $THColour = "#4f5960";

        // Assign legend colours to order types

        $BITextColour = "text-light-blue"; // Book Ins  		Light Blue
        $BOTextColour = "text-yellow"; // Book Outs  		Yellow
        $BTTextColour = "text-navy"; // Branch Transfers	Navy
        $CRTextColour = "text-red"; // Credits  		Red
        $DNTextColour = "text-teal"; // Debit Notes  	Teal
        $QTTextColour = "text-aqua"; // Quotations  		Aqua
        $SLTextColour = "text-green"; // Orders  			Green
        $WOTextColour = "text-gray"; // Works Orders  	Gray
        $RWTextColour = "text-rwcolor";
        $TCTextColour = "text-tccolor";
        $THTextColour = "text-thcolor";
        $todaysordersbytypedata = "[";

        $i = 1;
        $tmp_total = 0;

        foreach ($todayOrders as $today) {
            $identifier = $today['identifier'];
            $value = $today['actualvalue1'];
            // The order type is the last two characters of the identifier
            $ordtype = substr($identifier, 10, 2);
            // Only interested in graphing order types that have a value
            if ($value <> 0) {
                $tmp_total += $value;

                // Set the colour, which is the order type followed by"Colour"
                switch ($ordtype) {
                    case "BI":
                        $colour = $BIColour;
                        $textcolour = $BITextColour;
                        $description = "Book Ins";
                        break;
                    case "BO":
                        $colour = $BOColour;
                        $textcolour = $BOTextColour;
                        $description = "Book Outs";
                        break;
                    case "BT":
                        $colour = $BTColour;
                        $textcolour = $BTTextColour;
                        $description = "Branch Transfers";
                        break;
                    case "CR":
                        $colour = $CRColour;
                        $textcolour = $CRTextColour;
                        $description = "Credit Notes";
                        break;
                    case "DN":
                        $colour = $DNColour;
                        $textcolour = $DNTextColour;
                        $description = "Debit Notes";
                        break;
                    case "QT":
                        $colour = $QTColour;
                        $textcolour = $QTTextColour;
                        $description = "Quotations";
                        break;
                    case "SL":
                        $colour = $SLColour;
                        $textcolour = $SLTextColour;
                        $description = "Sales Orders";
                        break;
                    case "WO":
                        $colour = $WOColour;
                        $textcolour = $WOTextColour;
                        $description = "Works Orders";
                        break;
                    case "RW":
                        $colour = $RWColour;
                        $textcolour = $RWTextColour;
                        $description = "Repairs & Warranty";
                        break;
                    case "TC":
                        $colour = $TCColour;
                        $textcolour = $TCTextColour;
                        $description = "Plant Hire Credit Note";
                        break;
                    case "TH":
                        $colour = $THColour;
                        $textcolour = $THTextColour;
                        $description = "Plant Hire Order";
                        break;
                }

                // The comma only comes in after the first set

                if ($i <> 1) {
                    $todaysordersbytypedata .= ",";
                }

                // Build the data string for the pie chart data
                $todaysordersbytypedata .= "{value:$value,color:'$colour',highlight:'$colour',label:'$ordtype'}";

                // Build the string for the legend
                $typeLink = site_url("site/todaysorder/" . $ordtype . "/type");
                $i++;
            }
        }
        $todaysordersbytypedata .= "]";

        $data["todaysordersbytypedata"] = $todaysordersbytypedata;

        echo json_encode($data);
    }

    public function TodaysOrdersCanvas(){
        $userId = 0;
        $branchNo = 0;
        $G_todaysdate = date("Y/m/d");
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }

        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }

        $G_branchno = null;
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $G_userId = $userId;
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }

        $repclause = $data["userDetail"]["repclause"];

        /// KPI last Update///
        $kpiLastupdate_val = $this->site_model->kpiLastupdate();
        $data["G_KPIsLastUpdatedDateTime"] = $kpiLastupdate_val["kpislastupdated"];

        // END KPI last Update///

        // GET THE DAY NUMBER AND WORKING DAYS//
        $kworkingDays = $this->site_model->workingDays($G_todaysdate);
        $data["dayno"] = $kworkingDays['dayno']; // Current working day number
        $data["totdays"] = $kworkingDays['totdays']; // Total number of working days in the month

        if (is_null($G_branchno)) {
            $G_branchno = $data['userDetail']['branch'];
        }

        $todayOrders = $this->site_model->todayOrders($G_level, $G_todaysdate, $repclause, $G_branchno);

        $BIColour = "#3c8dbc"; // Book Ins  		Light Blue
        $BOColour = "#f39c12"; // Book Outs  		Yellow
        $BTColour = "#001f3f"; // Branch Transfers	Navy
        $CRColour = "#dd4b39"; // Credits  		Red
        $DNColour = "#39cccc"; // Debit Notes  	Teal
        $QTColour = "#00c0ef"; // Quotations  		Aqua
        $SLColour = "#00a65a"; // Orders  			Green
        $WOColour = "#d2d6de"; // Works Orders  	Gray
        $RWColour = "#f44295";
        $TCColour = "#7a1919";
        $THColour = "#4f5960";

        // Assign legend colours to order types

        $BITextColour = "text-light-blue"; // Book Ins  		Light Blue
        $BOTextColour = "text-yellow"; // Book Outs  		Yellow
        $BTTextColour = "text-navy"; // Branch Transfers	Navy
        $CRTextColour = "text-red"; // Credits  		Red
        $DNTextColour = "text-teal"; // Debit Notes  	Teal
        $QTTextColour = "text-aqua"; // Quotations  		Aqua
        $SLTextColour = "text-green"; // Orders  			Green
        $WOTextColour = "text-gray"; // Works Orders  	Gray
        $RWTextColour = "text-rwcolor";
        $TCTextColour = "text-tccolor";
        $THTextColour = "text-thcolor";
        $todaysordersbytypedata = "[";

        $i = 1;
        $tmp_total = 0;

        foreach ($todayOrders as $today) {
            $identifier = $today['identifier'];
            $value = $today['actualvalue1'];
            // The order type is the last two characters of the identifier
            $ordtype = substr($identifier, 10, 2);
            // Only interested in graphing order types that have a value
            if ($value <> 0) {
                $tmp_total += $value;

                // Set the colour, which is the order type followed by"Colour"
                switch ($ordtype) {
                    case "BI":
                        $colour = $BIColour;
                        $textcolour = $BITextColour;
                        $description = "Book Ins";
                        break;
                    case "BO":
                        $colour = $BOColour;
                        $textcolour = $BOTextColour;
                        $description = "Book Outs";
                        break;
                    case "BT":
                        $colour = $BTColour;
                        $textcolour = $BTTextColour;
                        $description = "Branch Transfers";
                        break;
                    case "CR":
                        $colour = $CRColour;
                        $textcolour = $CRTextColour;
                        $description = "Credit Notes";
                        break;
                    case "DN":
                        $colour = $DNColour;
                        $textcolour = $DNTextColour;
                        $description = "Debit Notes";
                        break;
                    case "QT":
                        $colour = $QTColour;
                        $textcolour = $QTTextColour;
                        $description = "Quotations";
                        break;
                    case "SL":
                        $colour = $SLColour;
                        $textcolour = $SLTextColour;
                        $description = "Sales Orders";
                        break;
                    case "WO":
                        $colour = $WOColour;
                        $textcolour = $WOTextColour;
                        $description = "Works Orders";
                        break;
                    case "RW":
                        $colour = $RWColour;
                        $textcolour = $RWTextColour;
                        $description = "Repairs & Warranty";
                        break;
                    case "TC":
                        $colour = $TCColour;
                        $textcolour = $TCTextColour;
                        $description = "Plant Hire Credit Note";
                        break;
                    case "TH":
                        $colour = $THColour;
                        $textcolour = $THTextColour;
                        $description = "Plant Hire Order";
                        break;
                }

                // The comma only comes in after the first set

                if ($i <> 1) {
                    $todaysordersbytypedata .= ",";
                }

                // Build the data string for the pie chart data
                $todaysordersbytypedata .= "{value:$value,color:'$colour',highlight:'$colour',label:'$ordtype'}";

                // Build the string for the legend
                $typeLink = site_url("site/todaysorder/" . $ordtype . "/type");

                $i++;
            }
        }
        $todaysordersbytypedata .= "]";

        $data["todaysordersbytypedata"] = $todaysordersbytypedata;

        $todayOrders = $this->site_model->todayOrdersStatus($G_level, $G_todaysdate, $repclause, $G_branchno);
        $ADVColour = "#f012be"; // Waiting advice note	Fuschia
        $COMColour = "#00a65a"; // Completed line		Green
        $CUSColour = "#39cccc"; // Call customer back	Teal
        $HLDColour = "#3d9970"; // Goods on hold		Olive
        $IBTColour = "#d2d6de"; // Inter-branch transfer	Gray
        $KITColour = "#01ff70"; // Process kit list		Lime
        $MEMColour = "#ff851b"; // Memo line			Orange
        $OFFColour = "#605ca8"; // Call off later		Purple
        $PIKColour = "#001f3f"; // Pick note printed	Navy
        $PROColour = "#3c8dbc"; // Process document		Light Blue
        $PURColour = "#dd4b39"; // Purchase order		Red
        $SBOColour = "#f39c12"; // Stock backorder		Yellow
        $WDLColour = "#00c0ef"; // Waiting delivery		Aqua
        $WRKColour = "#d81b60"; // Create works order	Maroon


        $todaysordersbystatusdata = "[";

        $i = 1;
        $tmp_total = 0;

        foreach ($todayOrders as $today) {

            $identifier = $today['identifier'];
            $value = $today['actualvalue1'];

            // The order type is the last three characters of the identifier
            $ordstatus = substr($identifier, 10, 3);
            // Only interested in graphing order statuses that have a value

            if ($value <> 0) {
                $tmp_total += $value;

                // Set the colour, which is the order status followed by"Colour"
                switch ($ordstatus) {
                    case "ADV":
                        $colour = $ADVColour;
                        break;
                    case "COM":
                        $colour = $COMColour;
                        break;
                    case "CUS":
                        $colour = $CUSColour;
                        break;
                    case "HLD":
                        $colour = $HLDColour;
                        break;
                    case "IBT":
                        $colour = $IBTColour;
                        break;
                    case "KIT":
                        $colour = $KITColour;
                        break;
                    case "MEM":
                        $colour = $MEMColour;
                        break;
                    case "OFF":
                        $colour = $OFFColour;
                        break;
                    case "PIK":
                        $colour = $PIKColour;
                        break;
                    case "PRO":
                        $colour = $PROColour;
                        break;
                    case "PUR":
                        $colour = $PURColour;
                        break;
                    case "SBO":
                        $colour = $SBOColour;
                        break;
                    case "WDL":
                        $colour = $WDLColour;
                        break;
                    case "WRK":
                        $colour = $WRKColour;
                        break;
                }

                // The comma only comes in after the first set

                if ($i <> 1) {
                    $todaysordersbystatusdata .= ",";
                }

                // Build the data string for the pie chart data
                $todaysordersbystatusdata .= "{value:$value,color:'$colour',highlight:'$colour',label:'$ordstatus'}";

                $i++;
            }
        }
        $todaysordersbystatusdata .= "]";
        $data["todaysordersbystatusdata"] = $todaysordersbystatusdata;

        echo json_encode($data);
    }

    public function OutStatusCanvas(){
        $userId = 0;
        $branchNo = 0;
        $G_todaysdate = date("Y/m/d");
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }

        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }

        $G_branchno = null;
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $G_userId = $userId;
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }

        $repclause = $data["userDetail"]["repclause"];

        /// KPI last Update///
        $kpiLastupdate_val = $this->site_model->kpiLastupdate();
        $data["G_KPIsLastUpdatedDateTime"] = $kpiLastupdate_val["kpislastupdated"];

        // END KPI last Update///

        // GET THE DAY NUMBER AND WORKING DAYS//
        $kworkingDays = $this->site_model->workingDays($G_todaysdate);
        $data["dayno"] = $kworkingDays['dayno']; // Current working day number
        $data["totdays"] = $kworkingDays['totdays']; // Total number of working days in the month

        if (is_null($G_branchno)) {
            $G_branchno = $data['userDetail']['branch'];
        }
        $outOrders = $this->site_model->outStandOrders($G_level, $G_todaysdate, $repclause, $G_branchno);
        $ADVColour = "#f012be"; // Waiting advice note	Fuschia
        $COMColour = "#00a65a"; // Completed line		Green
        $CUSColour = "#39cccc"; // Call customer back	Teal
        $HLDColour = "#3d9970"; // Goods on hold		Olive
        $IBTColour = "#d2d6de"; // Inter-branch transfer	Gray
        $KITColour = "#01ff70"; // Process kit list		Lime
        $MEMColour = "#ff851b"; // Memo line			Orange
        $OFFColour = "#605ca8"; // Call off later		Purple
        $PIKColour = "#001f3f"; // Pick note printed	Navy
        $PROColour = "#3c8dbc"; // Process document		Light Blue
        $PURColour = "#dd4b39"; // Purchase order		Red
        $SBOColour = "#f39c12"; // Stock backorder		Yellow
        $WDLColour = "#00c0ef"; // Waiting delivery		Aqua
        $WRKColour = "#d81b60"; // Create works order	Maroon

        // The pie chart data string looks something like this : [{value:179.80,color:'#dd4b39',highlight:'#dd4b39',label:'CR'},{value:1307.96,color:'#00a65a',highlight:'#00a65a',label:'SL'}]

        $outstandingordersbystatusdata = "[";

        $i = 1;
        $tmp_total = 0;

        foreach ($outOrders as $outor) {
            $identifier = $outor["identifier"];
            $value = $outor["actualvalue1"];

            // The order type is the last three characters of the identifier
            $ordstatus = substr($identifier, 10, 3);
            // Only interested in graphing order statuses that have a value

            if ($value <> 0) {
                $tmp_total += $value;

                // Set the colour, which is the order status followed by"Colour"
                switch ($ordstatus) {
                    case "ADV":
                        $colour = $ADVColour;
                        break;
                    case "COM":
                        $colour = $COMColour;
                        break;
                    case "CUS":
                        $colour = $CUSColour;
                        break;
                    case "HLD":
                        $colour = $HLDColour;
                        break;
                    case "IBT":
                        $colour = $IBTColour;
                        break;
                    case "KIT":
                        $colour = $KITColour;
                        break;
                    case "MEM":
                        $colour = $MEMColour;
                        break;
                    case "OFF":
                        $colour = $OFFColour;
                        break;
                    case "PIK":
                        $colour = $PIKColour;
                        break;
                    case "PRO":
                        $colour = $PROColour;
                        break;
                    case "PUR":
                        $colour = $PURColour;
                        break;
                    case "SBO":
                        $colour = $SBOColour;
                        break;
                    case "WDL":
                        $colour = $WDLColour;
                        break;
                    case "WRK":
                        $colour = $WRKColour;
                        break;
                }

                // The comma only comes in after the first set

                if ($i <> 1) {
                    $outstandingordersbystatusdata .= ",";
                }
                // Build the data string for the pie chart data
                $outstandingordersbystatusdata .= "{value:$value,color:'$colour',highlight:'$colour',label:'$ordstatus'}";
                $i++;
            }
        }
        $outstandingordersbystatusdata .= "]";

        $data["outstandingordersbystatusdata"] = $outstandingordersbystatusdata;

        echo json_encode($data);
    }

    public function ThreeYearChartCanvas(){
        $data['year0'] = date("Y");
        $data['year1'] = $data['year0'] - 1;
        $data['year2'] = $data['year0'] - 2;
        $data['year3'] = $data['year0'] - 3;

        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        $targetUserId = $this->session->userdata("userid");
        $userId = 0;
        $branchNo = 0;

        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }

        $G_branchno = null;

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $G_userId = $userId;
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        // Initialise sales array

        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();

        // Preload the year and month into an array so that we can make sure we load the sales against the correct row. Pad the month with leading 0 if needed. Had an example where
        // a rep started more recently that three years ago, and therefore there was less than 36 months. It was loading all these into the start of the array, rather than against the
        // appropriate row.

        $data['tmpyear'] = $data['year3']; //CR0001 $year3;
        $data['tmpmonth'] = 1; // CR0001 $thismonth + 1;

        for ($x = 0; $x < 48; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        // Get sales for the sales rep
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x < 48; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }
        $data['yearstartmonth'] = $this->site_model->getYearStartMonth();
        $start_month_delta = $data['yearstartmonth'] <= date('m') ? 11 + $data['yearstartmonth'] : $data['yearstartmonth'] - 1;
        $data['year0data'] = $this->site_model->GetYearData($data['sales'], 24 + $start_month_delta, 35 + $start_month_delta);
        $data['cumulativeYear0ChartValues'] = $this->site_model->GetCumulativeYearData($data['sales'], 24 + $start_month_delta, 35 + $start_month_delta);
        $data['year0ChartValues'] = $data['year0data'];
        $data['salesTargetForLastThreeYear'] = $this->site_model->getSalesTargetForLastThreeYear($G_level, $data['yearmonth'], $data['sales'], $targetUserId, $G_branchno);
        $data['targetDataForCurrentYear'] = $this->site_model->GetTargetDataForCurrentYear($data['salesTargetForLastThreeYear']);
        $data['cumulativeYear1ChartValues'] = $this->site_model->GetCumulativeYearData($data['sales'], 12 + $start_month_delta, 23 + $start_month_delta);
        $data['cumulativeYear2ChartValues'] = $this->site_model->GetCumulativeYearData($data['sales'], $start_month_delta, 11 + $start_month_delta);
        $data['cumulativeTargetDataForCurrentYear'] = $this->site_model->GetCumulativeTargetDataForCurrentYear($data['salesTargetForLastThreeYear']);
        $data['year0data'] = $this->site_model->GetYearData($data['sales'], 24 + $start_month_delta, 35 + $start_month_delta);
        $data['year1data'] = $this->site_model->GetYearData($data['sales'], 12 + $start_month_delta, 23 + $start_month_delta);
        $data['year2data'] = $this->site_model->GetYearData($data['sales'], $start_month_delta, 11 + $start_month_delta);
        $data['year0ChartValues'] = $data['year0data'];
        $data['year1ChartValues'] = $data['year1data'];
        $data['year2ChartValues'] = $data['year2data'];
        echo json_encode($data);
    }

    public function ThisYearCmlVsTargetChart(){
        $data['year0'] = date("Y");
        $data['year1'] = $data['year0'] - 1;
        $data['year2'] = $data['year0'] - 2;
        $data['year3'] = $data['year0'] - 3;

        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        $targetUserId = $this->session->userdata("userid");
        $userId = 0;
        $branchNo = 0;

        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }

        $G_branchno = null;

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $G_userId = $userId;
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        // Initialise sales array

        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();

        // Preload the year and month into an array so that we can make sure we load the sales against the correct row. Pad the month with leading 0 if needed. Had an example where
        // a rep started more recently that three years ago, and therefore there was less than 36 months. It was loading all these into the start of the array, rather than against the
        // appropriate row.

        $data['tmpyear'] = $data['year3']; //CR0001 $year3;
        $data['tmpmonth'] = 1; // CR0001 $thismonth + 1;

        for ($x = 0; $x < 48; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        // Get sales for the sales rep
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x < 48; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }
        $data['yearstartmonth'] = $this->site_model->getYearStartMonth();
        $start_month_delta = $data['yearstartmonth'] <= date('m') ? 11 + $data['yearstartmonth'] : $data['yearstartmonth'] - 1;
        
        $data['year0ChartValues'] = $data['year0data'];
        $data['salesTargetForLastThreeYear'] = $this->site_model->getSalesTargetForLastThreeYear($G_level, $data['yearmonth'], $data['sales'], $targetUserId, $G_branchno);
        $data['targetDataForCurrentYear'] = $this->site_model->GetTargetDataForCurrentYear($data['salesTargetForLastThreeYear']);
        $data['cumulativeTargetDataForCurrentYear'] = $this->site_model->GetCumulativeTargetDataForCurrentYear($data['salesTargetForLastThreeYear']);
        echo json_encode($data);
    }

    public function ThisYearVsLastYearChart(){
        $data['year0'] = date("Y");
        $data['year1'] = $data['year0'] - 1;
        $data['year2'] = $data['year0'] - 2;
        $data['year3'] = $data['year0'] - 3;

        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        $targetUserId = $this->session->userdata("userid");
        $userId = 0;
        $branchNo = 0;

        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }

        $G_branchno = null;

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $G_userId = $userId;
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        // Initialise sales array

        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();

        // Preload the year and month into an array so that we can make sure we load the sales against the correct row. Pad the month with leading 0 if needed. Had an example where
        // a rep started more recently that three years ago, and therefore there was less than 36 months. It was loading all these into the start of the array, rather than against the
        // appropriate row.

        $data['tmpyear'] = $data['year3']; //CR0001 $year3;
        $data['tmpmonth'] = 1; // CR0001 $thismonth + 1;

        for ($x = 0; $x < 48; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        // Get sales for the sales rep
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x < 48; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }
        $data['yearstartmonth'] = $this->site_model->getYearStartMonth();
        $start_month_delta = $data['yearstartmonth'] <= date('m') ? 11 + $data['yearstartmonth'] : $data['yearstartmonth'] - 1;
        $data['year0data'] = $this->site_model->GetYearData($data['sales'], 24 + $start_month_delta, 35 + $start_month_delta);
        $data['year1data'] = $this->site_model->GetYearData($data['sales'], 12 + $start_month_delta, 23 + $start_month_delta);
        $data['year2data'] = $this->site_model->GetYearData($data['sales'], $start_month_delta, 11 + $start_month_delta);
        $data['year0ChartValues'] = $data['year0data'];
        $data['year1ChartValues'] = $data['year1data'];
        $data['year2ChartValues'] = $data['year2data'];
        echo json_encode($data);
    }

    public function ThisYearCmlVsLastYearCmlChart(){
        $data['year0'] = date("Y");
        $data['year1'] = $data['year0'] - 1;
        $data['year2'] = $data['year0'] - 2;
        $data['year3'] = $data['year0'] - 3;

        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));

        $userType = $data['userType'] = $data['userDetail']['usertype'];
        $targetUserId = $this->session->userdata("userid");
        $userId = 0;
        $branchNo = 0;

        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }

        $G_branchno = null;

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {
            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $G_userId = $userId;
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        // Initialise sales array

        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();

        // Preload the year and month into an array so that we can make sure we load the sales against the correct row. Pad the month with leading 0 if needed. Had an example where
        // a rep started more recently that three years ago, and therefore there was less than 36 months. It was loading all these into the start of the array, rather than against the
        // appropriate row.

        $data['tmpyear'] = $data['year3']; //CR0001 $year3;
        $data['tmpmonth'] = 1; // CR0001 $thismonth + 1;

        for ($x = 0; $x < 48; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        // Get sales for the sales rep
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x < 48; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }
        $data['yearstartmonth'] = $this->site_model->getYearStartMonth();
        $start_month_delta = $data['yearstartmonth'] <= date('m') ? 11 + $data['yearstartmonth'] : $data['yearstartmonth'] - 1;
        $data['cumulativeYear0ChartValues'] = $this->site_model->GetCumulativeYearData($data['sales'], 24 + $start_month_delta, 35 + $start_month_delta);
        $data['cumulativeYear1ChartValues'] = $this->site_model->GetCumulativeYearData($data['sales'], 12 + $start_month_delta, 23 + $start_month_delta);
        $data['cumulativeYear2ChartValues'] = $this->site_model->GetCumulativeYearData($data['sales'], $start_month_delta, 11 + $start_month_delta);
        echo json_encode($data);
    }

    /* Function to set the session of the current selected branch */

    public function set_selected_branch_session($branchno)
    {

        if (!empty($branchno) || $branchno > 0) {
            $branchname = $this->site_model->getBranch($branchno);
            $data = array(
                "selectedBranch" => array(
                    "branchno" => $branchno,
                    "name" => $branchname
                )
            );
        } else {
            $data = array(
                "selectedBranch" => array(
                    "branchno" => 0,
                    "name" => 'Company Level'
                )
            );
        }
        $this->session->set_userdata($data);
        $this->session->unset_userdata('selectedUser');
        $this->session->userdata('selectedBranch');
        redirect('dashboard');
    }

    /* Function to get all the branches */

    public function getAllBranches()
    {
        $this->load->model('users/users_model');
        header('Content-Type: application/json');

        $branches = $this->site_model->getAllBranches();
        $userid = $this->session->userdata("userid");
        $userDetail = $this->users_model->getUserDetails($userid);
        $usertype = $userDetail['usertype'];
        $selectedBranch = $this->session->userdata("selectedBranch");
        if (empty($selectedBranch)) {
            $selectedBranch['branchno'] = '0';
            $selectedBranch['name'] = 'Company Level';
        } else {
            //$lis[0] = "<li><a href='".site_url("site/set_selected_branch_session/0")."'>Company Level</a></li>";
        }
        $listIndex = 1;
        $lis[0] = "<li><a href='" . site_url("site/set_selected_branch_session/0") . "'>Company Level</a></li>";
        foreach ($branches as $branch) {
            $lis[$listIndex] = "<li><a href='" . site_url("site/set_selected_branch_session/" . $branch['branch']) . "'>" . $branch['name'] . "</a></li>";
            $listIndex++;
        }
        $li = implode("", $lis);
        echo json_encode(array('branches' => $li, 'selectedBranch' => $selectedBranch, 'usertype' => $usertype));
        exit;
    }

    public function daydrillreport()
    {
        if ($this->site_model->is_logged_in() == false) {
            redirect('/');
        }
        setcookie($this->config->item('site_name') . '_' . $this->session->userdata('userid') . '_last_visited', current_url(), time() + (86400 * 365), "/"); // 86400 = 1 day
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));
        $data['lastsalesdate'] = $this->site_model->getMaxDate($data['userDetail']['repwhere']);
        $data['lastsalesdate'] = date('d/m/Y', strtotime($data['lastsalesdate']));
        $data['main_content'] = 'daydrillreport';
        $this->load->view('customer/front_template', $data);
    }

    public function salesmtdreport()
    {
        if ($this->site_model->is_logged_in() == false) {
            redirect('/');
        }
        setcookie($this->config->item('site_name') . '_' . $this->session->userdata('userid') . '_last_visited', current_url(), time() + (86400 * 365), "/"); // 86400 = 1 day
        $data['main_content'] = 'salesmtdreport';
        $this->load->view('customer/front_template', $data);
    }

    public function getdaydrillreport()
    {
        header('Content-Type: application/json');
        $userId = 0;
        $branchNo = 0;

        $userDetail = $this->site_model->getUserDetails($this->session->userdata('userid'));
        $lastsalesdate = $this->site_model->getMaxDate($userDetail['repwhere']);
        $lastsalesdate = strtotime($lastsalesdate);
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }

        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $limit = 10;
        $start = isset($_POST["start"]) ? $_POST["start"] : 0;
        $length = isset($_POST["length"]) ? $_POST["length"] : $limit;
        $search = isset($_POST["search"]) ? $_POST["search"] : array();
        $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
        $search_key = $search['value'];
        $specific_search = $this->findPostedSpecificSearchAndMakec();
        $specific_order = $this->findPostedOrder();
        $recodeArray = $this->site_model->getUsersRepcodeCustom($userId);
        $totalData = $this->site_model->dayDrillData($lastsalesdate, $specific_search, $specific_order, $search_key, '', '', 1, $recodeArray, $branchNo);
        $reportData = $this->site_model->dayDrillData($lastsalesdate, $specific_search, $specific_order, $search_key, $start, $length, '2', $recodeArray, $branchNo);
        $return_array = array(
            "draw" => $draw,
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalData,
            "data" => $reportData
        );
        echo json_encode($return_array);
        exit;
    }

    public function getsalesmtdreport()
    {
        header('Content-Type: application/json');
        $userId = 0;
        $branchNo = 0;

        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }

        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $limit = 10;
        $start = isset($_POST["start"]) ? $_POST["start"] : 0;
        $length = isset($_POST["length"]) ? $_POST["length"] : $limit;
        $search = isset($_POST["search"]) ? $_POST["search"] : array();
        $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
        $search_key = $search['value'];
        $specific_search = $this->findPostedSpecificSearchAndMakec();
        $specific_order = $this->findPostedOrder();
        $recodeArray = $this->site_model->getUsersRepcodeCustom($userId);
        $totalData = $this->site_model->salesmtdData($specific_search, $specific_order, $search_key, '', '', 1, $recodeArray, $branchNo);
        $reportData = $this->site_model->salesmtdData($specific_search, $specific_order, $search_key, $start, $length, '2', $recodeArray, $branchNo);
        $return_array = array(
            "draw" => $draw,
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalData,
            "data" => $reportData
        );
        echo json_encode($return_array);
        exit;
    }

    public function findPostedSpecificSearchAndMakec()
    {
        $posted_columns = $_POST['columns'];
        $search_keys = $this->getSpecificSearchKeys();
        $search = array();
        foreach ($posted_columns as $key => $col) {
            $search[$search_keys[$key]] = $col['search']['value'];
        }

        return $search;
    }

    public function getSpecificSearchKeys()
    {

        $search_keys = array('salesanalysis.account', 'customer.name', 'salesanalysis.orderno', 'salesanalysis.ordtype', 'salesanalysis.prodcode', 'product.description', 'salesanalysis.repcode', 'salesanalysis.quantity', 'salesanalysis.sales', 'salesanalysis.date');
        return $search_keys;
    }

    public function findPostedOrder()
    {
        $posted_order = $_POST['order'];
        $column_index = -1;
        $order = array(
            'by' => $search_keys[0],
            'dir' => 'asc'
        );

        if (isset($posted_order[0]['column']) && isset($posted_order[0]['dir'])) {
            $column_index = $posted_order[0]['column'];

        }

        $search_keys = $this->getSpecificSearchKeys();
        if ($column_index >= 0) {
            $order = array(
                'by' => $search_keys[$column_index],
                'dir' => $posted_order[0]['dir']
            );
        } else {
            $order = array(
                'by' => $search_keys[0],
                'dir' => 'asc'
            );
        }

        return $order;
    }

    public function daydrill_excel_export()
    {
        $search_key = $this->uri->segment(3);
        $specific_search_keys = $this->getSpecificSearchKeys();
        header("Content-type: text/x-csv");
        //$previousDate=date('Y_m_d',strtotime("-1 days"));
        $csvName = 'day-drill-report.csv';
        header("Content-Disposition: attachment;filename=" . $csvName . "");
        header("Cache-Control: max-age=0");
        $userId = 0;
        $branchNo = 0;
        $userDetail = $this->site_model->getUserDetails($this->session->userdata('userid'));
        $lastsalesdate = $this->site_model->getMaxDate($userDetail['repwhere']);
        $lastsalesdate = strtotime($lastsalesdate);
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $specific_search = $this->findPostedSpecificSearchAndMakec();
        $recodeArray = $this->site_model->getUsersRepcodeCustom($userId);
        $xlsOutput = $this->site_model->csv_daydrill_export($lastsalesdate, $specific_search, $search_key, $recodeArray, $branchNo);

        echo $xlsOutput;
        exit();
    }

    public function salesmtd_excel_exportcustom()
    {
        $search_key = $this->uri->segment($i);
        $specific_search_keys = $this->getSpecificSearchKeys();
        header("Content-type: text/x-csv");
        $csvName = 'sales-mtd-report.csv';
        header("Content-Disposition: attachment;filename=" . $csvName . "");
        header("Cache-Control: max-age=0");
        $userId = 0;
        $branchNo = 0;
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        $specific_search = $this->findPostedSpecificSearchAndMakec();
        $recodeArray = $this->site_model->getUsersRepcodeCustom($userId);
        //print_r($recodeArray);die('hello');
        $xlsOutput = $this->site_model->csv_mtd_export($specific_search, $search_key, $recodeArray, $branchNo);
        //$xlsOutput = $this->site_model->csv_daydrill_export($specific_search,$search_key,$recodeArray,$branchNo);

        echo $xlsOutput;
        exit();
    }

    public function todaysorder($segment, $by)
    {
        if (!empty($segment) && !empty($by)) {
            if ($this->site_model->is_logged_in() == false) {
                redirect('/');
            }
            setcookie($this->config->item('site_name') . '_' . $this->session->userdata('userid') . '_last_visited', current_url(), time() + (86400 * 365), "/"); // 86400 = 1 day
            $userId = 0;
            $branchNo = 0;
            if ($by == "type") {
                $segmentArray = array("BI" => "Book Ins", "BO" => "Book Outs", "BT" => "Branch Transfers", "CR" => "Credit Notes", "DN" => "Debit Notes", "QT" => "Quotations", "SL" => "Sales Orders", "WO" => "Works Orders", "RW" => "Repairs & Warranty");
            } else {
                $segmentArray = array("ADV" => "Waiting Advice Note", "COM" => "Completed Line", "CUS" => "Call Customer Back", "HLD" => "Goods On Hold", "IBT" => "Inter-Branch Transfer", "KIT" => "Process Kit List", "MEM" => "Memo Line (Quotations)", "OFF" => "Call Off Later", "PIK" => "Pick Note Printed", "PRO" => "Process Document", "PUR" => "Purchase Order", "SBO" => "Stock Backorder", "WDL" => "Waiting Delivery", "WRK" => "Create Works Order");
            }
            if (isset($segmentArray[$segment])) {
                $data['headTitle'] = $segmentArray[$segment];
            } else {
                redirect('/');
            }
            if (count($this->session->userdata('selectedUser')) > 0) {
                $UserSes = $this->session->userdata('selectedUser');
                $userId = $UserSes["userid"];
            }

            if (count($this->session->userdata('selectedBranch')) > 0) {
                $branchSes = $this->session->userdata('selectedBranch');
                $branchNo = $branchSes["branchno"];
            }
            $recodeArray = $this->site_model->getUsersRepcodeCustom($userId);
            $data['reportData'] = $this->site_model->todayOrdersBySegment($recodeArray, $branchNo, $segment, $by);
            $data['main_content'] = 'segmentreport';
            $this->load->view('customer/front_template', $data);
        } else {
            redirect('/');
        }
    }

    public function outstandingorder($segment, $by)
    {
        if (!empty($segment) && !empty($by)) {
            if ($this->site_model->is_logged_in() == false) {
                redirect('/');
            }
            setcookie($this->config->item('site_name') . '_' . $this->session->userdata('userid') . '_last_visited', current_url(), time() + (86400 * 365), "/"); // 86400 = 1 day
            $userId = 0;
            $branchNo = 0;
            if ($by == "status") {
                $segmentArray = array("ADV" => "Waiting Advice Note", "COM" => "Completed Line", "CUS" => "Call Customer Back", "HLD" => "Goods On Hold", "IBT" => "Inter-Branch Transfer", "KIT" => "Process Kit List", "MEM" => "Memo Line (Quotations)", "OFF" => "Call Off Later", "PIK" => "Pick Note Printed", "PRO" => "Process Document", "PUR" => "Purchase Order", "SBO" => "Stock Backorder", "WDL" => "Waiting Delivery", "WRK" => "Create Works Order");
            }
            if (isset($segmentArray[$segment])) {
                $data['headTitle'] = $segmentArray[$segment];
            } else {
                redirect('/');
            }
            if (count($this->session->userdata('selectedUser')) > 0) {
                $UserSes = $this->session->userdata('selectedUser');
                $userId = $UserSes["userid"];
            }

            if (count($this->session->userdata('selectedBranch')) > 0) {
                $branchSes = $this->session->userdata('selectedBranch');
                $branchNo = $branchSes["branchno"];
            }
            $recodeArray = $this->site_model->getUsersRepcodeCustom($userId);
            $data['reportData'] = $this->site_model->OutstandingOrdersBySegment($recodeArray, $branchNo, $segment, $by);
            $data['main_content'] = 'outstandingsegmentreport';
            $this->load->view('customer/front_template', $data);
        } else {
            redirect('/');
        }
    }

    public function getprojectedmonthdata($stat, $currdatemonthindicator)
    {

        //  echo $currdatemonthindicator;
        if ($stat == 'prev') {
            $month = date('n', strtotime($currdatemonthindicator . ' -1 month'));
            $year = date('Y', strtotime($currdatemonthindicator . ' -1 month'));
        } else {
            $month = date('n', strtotime($currdatemonthindicator . ' +1 month'));
            $year = date('Y', strtotime($currdatemonthindicator . ' +1 month'));
        }
        $returnArray = array();
        $d = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if ((date('m', strtotime($currdatemonthindicator)) == date('m', strtotime('-1 month'))) && ($stat == 'next')) {
            $dateofMonth = date('d', time());
        } else {
            $dateofMonth = $d;
        }
        $G_todaysdate = date("Y/m/d", strtotime($year . '-' . $month . '-' . $dateofMonth));
        //echo $dateofMonth.'  '.$G_todaysdate;die;
        $daysinmonth = date("t", strtotime($G_todaysdate));
        $data['year0'] = date("Y");
        $data['year1'] = $data['year0'] - 1;
        $data['year2'] = $data['year0'] - 2;
        $data['curyearmonth'] = (date('Y', time()) * 100) + $month;
        $date = $G_todaysdate;
        $row = $this->site_model->workingDays($date);
        $data['dayno'] = $row['dayno']; // Current working day number
        $data['totdays'] = $row['totdays'];
        $G_userid = $this->session->userdata("userid");
        $data['userDetail'] = $this->site_model->getUserDetails($this->session->userdata('userid'));
        $userType = $data['userType'] = $data['userDetail']['usertype'];
        $userId = 0;
        $branchNo = 0;
        if (count($this->session->userdata('selectedUser')) > 0) {
            $UserSes = $this->session->userdata('selectedUser');
            $userId = $UserSes["userid"];
        }
        if (count($this->session->userdata('selectedBranch')) > 0) {
            $branchSes = $this->session->userdata('selectedBranch');
            $branchNo = $branchSes["branchno"];
        }
        if ($userType == "B") {
            $G_level = "branch";
        }

        if ($userType == "B") {
            $G_level = "Branch";
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId == 0)) {
            $G_level = "Company";
        } elseif (($userType == "A") && ($branchNo > 0) && ($userId == 0)) {

            $G_level = "Branch";
            $G_branchno = $branchNo;
        } elseif (($userType == "A") && ($branchNo == 0) && ($userId > 0)) {
            $G_level = "User";
            $G_userId = $userId;
            $data['userDetail'] = $this->site_model->getUserDetails($userId);
        } else {
            $G_level = "User";
        }
        if ($G_branchno == 0 && $G_level != 'Branch') {
            $G_branchno = $data['userDetail']['branch'];
        }
        $userKpi = $this->site_model->userKpi($G_level, $G_branchno, $G_userid);

        $data = $this->site_model->GetKpiDataForTwoYearVsTargetChart($userKpi, $data, $G_level);

        $returnArray['G_kpithreshold1'] = $data["G_kpithreshold1"];
        $returnArray['G_kpithreshold2'] = $data["G_kpithreshold2"];
        $targetDataMonth = $this->site_model->getMonthTargetData($data['curyearmonth'], $userId, $branchNo, $G_level);

        $data['G_MonthlySalesTarget'] = $targetDataMonth['saletarget'];
        $data['yearmonth'] = array();
        $data['monthnames'] = array();
        $data['sales'] = array();
        $data['costs'] = array();
        $data['tmpyear'] = $data['year2']; //CR0001 $year3;
        $data['tmpmonth'] = 1;
        $data['startyearmonth'] = ($data['year2'] * 100) + 1;
        $data['curyearmonth'] = ($data['year0'] * 100) + $month;
        for ($x = 0; $x <= 36; $x++) {
            $data['yearmonth'][$x] = ($data['tmpyear'] * 100) + $data['tmpmonth'];

            $data['sales'][$x] = 0;
            $data['costs'][$x] = 0;

            $data['tmpmonth'] = $data['tmpmonth'] + 1;
            if ($data['tmpmonth'] == 13) {
                $data['tmpmonth'] = 1;
                $data['tmpyear'] = $data['tmpyear'] + 1;
            }
        }
        $result = $this->site_model->getSalesAnalisys($data['curyearmonth'], $data['userDetail']['repwhere'], $G_userid, $G_branchno, $G_level);

        $x = 0;

        foreach ($result as $row) {
            $data['salessummaryyearmonth'] = $row['yearmonth'];
            $data['salessummarysales'] = $row['sales'];
            $data['salessummarycost'] = $row['cost'];

            // For each data row, loop through the array and put the sales value in the correct place

            for ($x = 0; $x <= 36; $x++) {
                if ($data['yearmonth'][$x] == $data['salessummaryyearmonth']) {
                    $data['sales'][$x] = $data['salessummarysales']; // If the year month of the data matches the array, put the value in
                    $data['costs'][$x] = $data['salessummarycost'];
                }
            }
        }
        $custyrmn = date('Ym', strtotime($G_todaysdate));
        $currkey = array_search($custyrmn, $data['yearmonth']);
        if (!empty($currkey)) {
            $data['monthlysales'] = $data['sales'][$currkey];
        } else {
            $data['monthlysales'] = 0;
        }

        $returnArray["projmonthsalespc"] = '';
        $data["projmonthsales"] = 0;
        
        if ($data["monthlysales"] <> 0) {
            $data["projdaysales"] = ($data["monthlysales"] / $data["dayno"]);
            $data["projmonthsales"] = $data["projdaysales"] * $data["totdays"]; // Extrapolate projected sales
            if ($data["G_MonthlySalesTarget"] <> 0) {
                $data["projmonthsalespc"] = ($data["projmonthsales"] / $data["G_MonthlySalesTarget"]) * 100;
            }
        }
        if (isset($data["projmonthsalespc"])) {
            $returnArray["projmonthsalespc"] = $data["projmonthsalespc"];
        }
        $repclause = $data["userDetail"]["repclause"];
        $cumday = $this->site_model->cumday($G_level, $G_todaysdate, $repclause, $G_branchno);
        //print_r($cumday);die;
        $cumday01sales = number_format($cumday[0]["SUM(day01sales)"], 0, '.', '');
        $cumday02sales = number_format($cumday[0]["SUM(day02sales)"] + $cumday01sales, 0, '.', '');
        $cumday03sales = number_format($cumday[0]["SUM(day03sales)"] + $cumday02sales, 0, '.', '');
        $cumday04sales = number_format($cumday[0]["SUM(day04sales)"] + $cumday03sales, 0, '.', '');
        $cumday05sales = number_format($cumday[0]["SUM(day05sales)"] + $cumday04sales, 0, '.', '');
        $cumday06sales = number_format($cumday[0]["SUM(day06sales)"] + $cumday05sales, 0, '.', '');
        $cumday07sales = number_format($cumday[0]["SUM(day07sales)"] + $cumday06sales, 0, '.', '');
        $cumday08sales = number_format($cumday[0]["SUM(day08sales)"] + $cumday07sales, 0, '.', '');
        $cumday09sales = number_format($cumday[0]["SUM(day09sales)"] + $cumday08sales, 0, '.', '');
        $cumday10sales = number_format($cumday[0]["SUM(day10sales)"] + $cumday09sales, 0, '.', '');
        $cumday11sales = number_format($cumday[0]["SUM(day11sales)"] + $cumday10sales, 0, '.', '');
        $cumday12sales = number_format($cumday[0]["SUM(day12sales)"] + $cumday11sales, 0, '.', '');
        $cumday13sales = number_format($cumday[0]["SUM(day13sales)"] + $cumday12sales, 0, '.', '');
        $cumday14sales = number_format($cumday[0]["SUM(day14sales)"] + $cumday13sales, 0, '.', '');
        $cumday15sales = number_format($cumday[0]["SUM(day15sales)"] + $cumday14sales, 0, '.', '');
        $cumday16sales = number_format($cumday[0]["SUM(day16sales)"] + $cumday15sales, 0, '.', '');
        $cumday17sales = number_format($cumday[0]["SUM(day17sales)"] + $cumday16sales, 0, '.', '');
        $cumday18sales = number_format($cumday[0]["SUM(day18sales)"] + $cumday17sales, 0, '.', '');
        $cumday19sales = number_format($cumday[0]["SUM(day19sales)"] + $cumday18sales, 0, '.', '');
        $cumday20sales = number_format($cumday[0]["SUM(day20sales)"] + $cumday19sales, 0, '.', '');
        $cumday21sales = number_format($cumday[0]["SUM(day21sales)"] + $cumday20sales, 0, '.', '');
        $cumday22sales = number_format($cumday[0]["SUM(day22sales)"] + $cumday21sales, 0, '.', '');
        $cumday23sales = number_format($cumday[0]["SUM(day23sales)"] + $cumday22sales, 0, '.', '');
        $cumday24sales = number_format($cumday[0]["SUM(day24sales)"] + $cumday23sales, 0, '.', '');
        $cumday25sales = number_format($cumday[0]["SUM(day25sales)"] + $cumday24sales, 0, '.', '');
        $cumday26sales = number_format($cumday[0]["SUM(day26sales)"] + $cumday25sales, 0, '.', '');
        $cumday27sales = number_format($cumday[0]["SUM(day27sales)"] + $cumday26sales, 0, '.', '');
        $cumday28sales = number_format($cumday[0]["SUM(day28sales)"] + $cumday27sales, 0, '.', '');
        $cumday29sales = number_format($cumday[0]["SUM(day29sales)"] + $cumday28sales, 0, '.', '');
        $cumday30sales = number_format($cumday[0]["SUM(day30sales)"] + $cumday29sales, 0, '.', '');
        $cumday31sales = number_format($cumday[0]["SUM(day31sales)"] + $cumday30sales, 0, '.', '');
        $data["ProjectedSalesMonthGraphActual"] = "$cumday01sales,$cumday02sales,$cumday03sales,$cumday04sales,$cumday05sales,$cumday06sales,$cumday07sales,$cumday08sales,$cumday09sales,$cumday10sales,$cumday11sales,$cumday12sales,$cumday13sales,$cumday14sales,$cumday15sales,$cumday16sales,$cumday17sales,$cumday18sales,$cumday19sales,$cumday20sales,$cumday21sales,$cumday22sales,$cumday23sales,$cumday24sales,$cumday25sales,$cumday26sales,$cumday27sales,$cumday28sales,$cumday29sales,$cumday30sales,$cumday31sales";
        $returnArray['ProjectedSalesMonthGraphActual'] = $data["ProjectedSalesMonthGraphActual"];
        $ProjectedSalesMonthGraphTarget = "";
        $ProjectedSalesMonthGraphProjected = "";
        $ProjectedSalesMonthGraphLabel = "";
        $data["daysinmonth"] = $daysinmonth;
        for ($x = 1; $x <= $daysinmonth; $x++) {
            $cumulativetarget[$x] = ($data["G_MonthlySalesTarget"] / $data["daysinmonth"]) * $x;
            $cumulativeprojected[$x] = ($data["projmonthsales"] / $data["daysinmonth"]) * $x;

            $ProjectedSalesMonthGraphTarget .= number_format($cumulativetarget[$x], 0, '.', '');
            $ProjectedSalesMonthGraphProjected .= number_format($cumulativeprojected[$x], 0, '.', '');

            // Only putting the first and last day number in the label as its too busy with all the days

            if ($x == 1 or $x == $daysinmonth) {
                $ProjectedSalesMonthGraphLabel .= "$x";
            } else {
                $ProjectedSalesMonthGraphLabel .= " ";
            }
            if ($x != $daysinmonth) {
                $ProjectedSalesMonthGraphTarget .= ",";
                $ProjectedSalesMonthGraphProjected .= ",";
                $ProjectedSalesMonthGraphLabel .= ",";
            }
        }
        $ProjectedSalesMonthGraphTarget .= "";
        $ProjectedSalesMonthGraphProjected .= "";
        $ProjectedSalesMonthGraphLabel .= ", ";
        $data["ProjectedSalesMonthGraphTarget"] = $ProjectedSalesMonthGraphTarget;
        $data["ProjectedSalesMonthGraphProjected"] = $ProjectedSalesMonthGraphProjected;
        $data["ProjectedSalesMonthGraphLabel"] = $ProjectedSalesMonthGraphLabel;
        $returnArray['ProjectedSalesMonthGraphTarget'] = $data["ProjectedSalesMonthGraphTarget"];
        $returnArray['ProjectedSalesMonthGraphProjected'] = $data["ProjectedSalesMonthGraphProjected"];
        $returnArray['ProjectedSalesMonthGraphLabel'] = $data["ProjectedSalesMonthGraphLabel"];
        $returnArray['fillColor'] = '';
        $returnArray['strokeColor'] = '';
        $returnArray['pointColor'] = '';
        $returnArray['pointStrokeColor'] = '';
        if (empty($returnArray['projmonthsalespc'])) {
            $returnArray['fillColor'] = '#00a65a';
        } elseif (empty($returnArray['projmonthsalespc'])) {
            $returnArray['fillColor'] = '#00a65a';
        } elseif ($returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold1']) {
            $returnArray['fillColor'] = '#dd4b39';
        } elseif ($returnArray['projmonthsalespc'] >= $returnArray['G_kpithreshold1'] and $returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold2']) {
            $returnArray['fillColor'] = '#f39c12';
        } elseif ($returnArray['projmonthsalespc'] > $returnArray['G_kpithreshold2']) {
            $returnArray['fillColor'] = '#00a65a';
        } else {
            $returnArray['fillColor'] = '#00000';
        }
        if (empty($returnArray['projmonthsalespc'])) {
            $returnArray['strokeColor'] = '#00a65a';
        } elseif ($returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold1']) {
            $returnArray['strokeColor'] = '#dd4b39';
        } elseif ($returnArray['projmonthsalespc'] >= $returnArray['G_kpithreshold1'] and $returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold2']) {
            $returnArray['strokeColor'] = '#f39c12';
        } elseif ($returnArray['projmonthsalespc'] > $returnArray['G_kpithreshold2']) {
            $returnArray['strokeColor'] = '#00a65a';
        } else {
            $returnArray['strokeColor'] = '#00000';
        }
        if (empty($returnArray['projmonthsalespc'])) {
            $returnArray['pointColor'] = '#00a65a';
        } elseif ($returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold1']) {
            $returnArray['pointColor'] = '#dd4b39';
        } elseif ($returnArray['projmonthsalespc'] >= $returnArray['G_kpithreshold1'] and $returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold2']) {
            $returnArray['pointColor'] = '#f39c12';
        } elseif ($returnArray['projmonthsalespc'] > $returnArray['G_kpithreshold2']) {
            $returnArray['pointColor'] = '#00a65a';
        } else {
            $returnArray['pointColor'] = '#00000';
        }
        if (empty($returnArray['projmonthsalespc'])) {
            $returnArray['pointStrokeColor'] = '#00a65a';
        } elseif ($returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold1']) {
            $returnArray['pointStrokeColor'] = '#dd4b39';
        } elseif ($returnArray['projmonthsalespc'] >= $returnArray['G_kpithreshold1'] and $returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold2']) {
            $returnArray['pointStrokeColor'] = '#f39c12';
        } elseif ($returnArray['projmonthsalespc'] > $returnArray['G_kpithreshold2']) {
            $returnArray['pointStrokeColor'] = '#00a65a';
        } else {
            $returnArray['pointStrokeColor'] = '#00000';
        }
        $returnArray['projColor'] = '';
        if ($returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold1']) {
            $returnArray['projColor'] = "text-red";
        }
        if ($returnArray['projmonthsalespc'] >= $returnArray['G_kpithreshold1'] and $returnArray['projmonthsalespc'] < $returnArray['G_kpithreshold2']) {
            $returnArray['projColor'] = "text-yellow";
        }
        if ($returnArray['projmonthsalespc'] >= $returnArray['G_kpithreshold2']) {
            $returnArray['projColor'] = "text-green";
        }
        if (empty($returnArray['projmonthsalespc'])) {
            $returnArray['projColor'] = "text-green";
        }

        unset($returnArray['G_kpithreshold1']);
        unset($returnArray['G_kpithreshold2']);
        unset($returnArray['projmonthsalespc']);
        $returnArray['monthyearindicator'] = date('M Y', strtotime($G_todaysdate));
        $returnArray['currdatemonthindicatorCust'] = date('Y-m-01', strtotime($G_todaysdate));
        $returnArray['disablenext'] = 0;
        if (date('m', strtotime($G_todaysdate)) == date('m')) {
            $returnArray['disablenext'] = 1;
        }

        echo json_encode($returnArray);
        die;
    }


    public function manage_cookie()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->helper('cookie');
            $cookie_name = $this->input->post('cookie_name');
            $cookie_value = $this->input->post('cookie_value');

            $cookie = array(
                'name' => $cookie_name,
                'value' => $cookie_value,
                'expire' => '315360000',
                //cookie expires in 10 years!
                'secure' => TRUE
            );
            set_cookie($cookie);
        } else {
            redirect('dashboard');
        }
    }


}