<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Models\Package;
use Altum\Models\User;
use Altum\Middlewares\Authentication;
use Altum\Response;
use Altum\Routing\Router;

class AdminUserExport extends Controller {

    public function index() {

        Authentication::guard('admin');

		ini_set('max_execution_time', '300');

        require_once APP_PATH . 'includes/PHPExcel.php';
		require_once APP_PATH . 'includes/PHPExcel/IOFactory.php';
		
		$users = Database::$database->query("SELECT count(`user_id`) as `total_users` FROM `users` LIMIT 1");
		$users = ($users->num_rows) ? $users->fetch_object() : null;
		$users = $users ? $users->total_users : null;
		
		$max_chunk = 1000;
		
		$total_chunk_user = ceil($users / $max_chunk);
		
		$xlsRow = 2;
		$xlsData = [];
		$is_report_exists = false;
		$file_name = 'Report-Users-' . date('Y-m-d-h-i') . '.xlsx';
		$report_file = UPLOADS_PATH . 'cache/'.$file_name;
		
		if (!file_exists($report_file)) {
			$objPHPExcel = new \PHPExcel();
		} else {
			$is_report_exists = true;
			$objPHPExcel = \PHPExcel_IOFactory::load($report_file);
			$xlsRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow()+1;
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'User ID');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Email');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Name');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Phone');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Phone');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Whitelabel');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Super Agency');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Agency');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Sub Agency');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Tanggal Daftar');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Total User');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Total Login');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Email Upline');
		
		for($g = 0; $g < $total_chunk_user; $g++) {
			if($this->user->type == 1 && (empty($this->user->whitelabel) && empty($this->user->superagency) && empty($this->user->agency) && empty($this->user->subagency))) {
				$user_rs = Database::$database->query("SELECT `a`.*, (SELECT COUNT(`user_id`) FROM `users` WHERE `ids_insert` = `a`.`user_id`) AS `total_user`, (SELECT DISTINCT `total_login` FROM `count_success_login_vw` WHERE `user_id` = `a`.`user_id` LIMIT 1) as `total_login`, (SELECT email FROM `users` WHERE `user_id` = `a`.`ids_insert` limit 1) AS `email_upline` FROM `users` `a` LIMIT ".$max_chunk." OFFSET ".($g*$max_chunk));
			} else {
				$user_rs = Database::$database->query("SELECT `a`.*, (SELECT COUNT(`user_id`) FROM `users` WHERE `ids_insert` = `a`.`user_id`) AS `total_user`, (SELECT DISTINCT `total_login` FROM `count_success_login_vw` WHERE `user_id` = `a`.`user_id` LIMIT 1) as `total_login`, (SELECT email FROM `users` WHERE `user_id` = `a`.`ids_insert` limit 1) AS `email_upline` FROM `users` `a` WHERE `a`.`ids_insert` = " . $this->user->user_id . " LIMIT ".$max_chunk." OFFSET ".($g*$max_chunk));
			}
			while($rows=$user_rs->fetch_object()) {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$xlsRow, $rows->user_id);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$xlsRow, $rows->email, \PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$xlsRow, $rows->name, \PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$xlsRow, $rows->phone, \PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$xlsRow, ($rows->active ? 'Aktif' : 'Tidak Aktif'), \PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$xlsRow, ($rows->whitelabel=='Y' ? 'Ya' : ''), \PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$xlsRow, ($rows->superagency=='Y' ? 'Ya' : ''), \PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$xlsRow, ($rows->agency=='Y' ? 'Ya' : ''), \PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$xlsRow, ($rows->subagency=='Y' ? 'Ya' : ''), \PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$xlsRow, \PHPExcel_Shared_Date::PHPToExcel($rows->date));
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$xlsRow, $rows->total_user, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$xlsRow, $rows->total_login, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('M'.$xlsRow, $rows->email_upline, \PHPExcel_Cell_DataType::TYPE_STRING);
				
				$objPHPExcel->getActiveSheet()->getStyle('J'.$xlsRow)->getNumberFormat()->setFormatCode('dd mmm yyyy hh:mm:ss');
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$xlsRow)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("D".$xlsRow.":M".$xlsRow)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$xlsRow++;
			}
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getStyle("A1:M1")->applyFromArray(array("font" => array("bold" => true)));
		$objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($report_file);
		
		header('Content-Type: application/json; charset=utf-8');
		
		Response::simple_json([
			'status' => 'success',
			'link' => SITE_URL . UPLOADS_URL_PATH . 'cache/'.$file_name
		]);
		
		die();

    }

}
