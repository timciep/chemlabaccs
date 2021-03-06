<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Using Highcharts

class Charts extends CI_Controller {

	function Charts()
	{
		parent::__construct();

		$this->view_data = array();
	}
	
	public function index()
	{
		$users = get_userID();
                $section = get_sections();
		$reports = get_latest_reports($this->db);
		$section_count = find_section_count($reports, $section);
                $user_count = find_user_count($reports, $users);
                $severity_percents = find_severity_percents($reports);
		$time_percents = find_time_percents($reports);
	/*	
		for($i = 1; $i < count($section); $i++)
		{
			$data = array($section_count[$i],$section_count[$i],$section_count[$i]);
			$series_data[] = array('name' => $section[$i], 'data' => $section_count);
		}
		
                for($i = 1; $i < count($users); $i++)
		{
			$data = array($user_count[$i],$user_count[$i],$user_count[$i]);
			$series_data[] = array('name' => $users[$i], 'data' => $data);
		}
	
                $series_data[] = array('name' => $users, 'data' => $severity_percents); //test
         */       
                $series_data[] = array('name' => $section, 'data' => $section_count); //test
		$severities = array("Low", "Medium", "High");
                $count = 0;
                
		foreach($severity_percents as $sev)
		{	
			$severity_data[] = array('name' => $severities[$count], 'data' => $sev);
			$count++;
		}
                
		//currently count serves as the names, need to change it to represent each time period
		$count = 1;
		foreach($time_percents as $time)
		{
			if($time > 0)
				$time_data[] = array('name' => $count, 'data' => $time);
			$count++;
		}
		
		
		$two_previous_month_count = find_month_count($reports, date("Y")-2);
		$month_data[] = array('name' => date("Y")-2, 'data' => $two_previous_month_count);
		$previous_month_count = find_month_count($reports, date("Y")-1);
		$month_data[] = array('name' => date("Y")-1, 'data' => $previous_month_count);
		$month_count = find_month_count($reports, date("Y"));
		$month_data[] = array('name' => date("Y"), 'data' => $month_count);
		for($i = 0; $i < count($month_count); $i++)
                { 
                    $month_count[$i] += $two_previous_month_count[$i] + $previous_month_count[$i]; 
                }
		$month_data[] = array('name' => 'Last 3 Years', 'data' => $month_count);
		
		$s1_month_count = find_month_sev_count($reports, 'Low');
		$sev_month_data[] = array('name' => '1 star', 'data' => $s1_month_count);
		$s2_month_count = find_month_sev_count($reports, 'Medium');
		$sev_month_data[] = array('name' => '2 stars', 'data' => $s2_month_count);
		$s3_month_count = find_month_sev_count($reports, 'High');
		$sev_month_data[] = array('name' => '3 stars', 'data' => $s3_month_count);
		for($i = 0; $i < count($month_count); $i++)
			$st_month_count[$i] = $s1_month_count[$i] + $s2_month_count[$i] + $s3_month_count[$i];
		$sev_month_data[] = array('name' => 'All Accidents', 'data' => $st_month_count);
		
		$title = "Big Data";
		$this->view_data['series_data'] = json_encode($series_data);
		$this->view_data['severity_data'] = $severity_data;
		$this->view_data['month_data'] = json_encode($month_data);
		$this->view_data['sev_month_data'] = json_encode($sev_month_data);
		$this->view_data['time_data'] = $time_data;
		$this->template->write("title", $title);
		$this->template->write_view("content", "view_charts", $this->view_data);
		$this->template->render();
	}
}
