<?php
include_once DIR_CATALOG . 'controller/payment/modulbanklib/ModulbankHelper.php';

class ControllerPaymentModulbank extends Controller
{
	private $error = array();

	public function index()
	{
		$data = [];
		$this->load->language('payment/modulbank');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('modulbank', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['help_total'] = $this->language->get('help_total');

		$data['text_enabled']   = $this->language->get('text_enabled');
		$data['text_disabled']  = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_order_status']        = $this->language->get('entry_order_status');
		$data['entry_order_refund_status'] = $this->language->get('entry_order_refund_status');
		$data['entry_geo_zone']            = $this->language->get('entry_geo_zone');

		$data['entry_card']    = $this->language->get('entry_card');
		$data['text_card_no']  = $this->language->get('text_card_no');
		$data['text_card_yes'] = $this->language->get('text_card_yes');
		$data['text_edit']     = $this->language->get('text_edit');

		$data['button_save']   = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['text_mode_test']     = $this->language->get('text_mode_test');
		$data['text_mode_prod']     = $this->language->get('text_mode_prod');
		$data['text_preauth_off']     = $this->language->get('text_preauth_off');
		$data['text_preauth_on']     = $this->language->get('text_preauth_on');
		$data['text_logging_off']   = $this->language->get('text_logging_off');
		$data['text_logging_on']    = $this->language->get('text_logging_on');
		$data['text_log_link']      = $this->url->link('payment/modulbank/logs', 'token=' . $this->session->data['token'], true);
		$data['text_download_logs'] = $this->language->get('text_download_logs');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if (isset($this->error['secret_key'])) {
			$data['error_secret_key'] = $this->error['secret_key'];
		} else {
			$data['error_secret_key'] = '';
		}

		if (isset($this->error['paymentname'])) {
			$data['error_paymentname'] = $this->error['paymentname'];
		} else {
			$data['error_paymentname'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'] , true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/modulbank', 'token=' . $this->session->data['token'], true),
		);

		$data['action'] = $this->url->link('payment/modulbank', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'] , true);

		$data['text_vat_0'] = $this->language->get('text_vat_0');
		$data['vat_list']   = array(
			'none'   => $this->language->get('text_vat_none'),
			'vat0'   => $this->language->get('text_vat_vat0'),
			'vat10'  => $this->language->get('text_vat_vat10'),
			'vat20'  => $this->language->get('text_vat_vat20'),
			'vat110' => $this->language->get('text_vat_vat110'),
			'vat120' => $this->language->get('text_vat_vat120'),
		);

		$data['sno_list'] = array(
			'osn'                => $this->language->get('text_sno_osn'),
			'usn_income'         => $this->language->get('text_sno_usn_income'),
			'usn_income_outcome' => $this->language->get('text_sno_usn_income_outcome'),
			'envd'               => $this->language->get('text_sno_envd'),
			'esn'                => $this->language->get('text_sno_esn'),
			'patent'             => $this->language->get('text_sno_patent'),
		);

		$data['payment_method_list'] = array(
			'full_prepayment' => $this->language->get('text_pm_full_prepayment'),
			'prepayment'      => $this->language->get('text_pm_prepayment'),
			'advance'         => $this->language->get('text_pm_advance'),
			'full_payment'    => $this->language->get('text_pm_full_payment'),
			'partial_payment' => $this->language->get('text_pm_partial_payment'),
			'credit'          => $this->language->get('text_pm_credit'),
			'credit_payment'  => $this->language->get('text_pm_credit_payment'),
		);

		$data['payment_object_list'] = array(
			'commodity'             => $this->language->get('text_po_commodity'),
			'excise'                => $this->language->get('text_po_excise'),
			'job'                   => $this->language->get('text_po_job'),
			'service'               => $this->language->get('text_po_service'),
			'gambling_bet'          => $this->language->get('text_po_gambling_bet'),
			'gambling_prize'        => $this->language->get('text_po_gambling_prize'),
			'lottery'               => $this->language->get('text_po_lottery'),
			'lottery_prize'         => $this->language->get('text_po_lottery_prize'),
			'intellectual_activity' => $this->language->get('text_po_intellectual_activity'),
			'payment'               => $this->language->get('text_po_payment'),
			'agent_commission'      => $this->language->get('text_po_agent_commission'),
			'composite'             => $this->language->get('text_po_composite'),
			'another'               => $this->language->get('text_po_another'),
		);

		$data['show_payment_methods_list'] = array(
			'sbp'  => $this->language->get('text_spm_sbp'),
			'card' => $this->language->get('text_spm_card'),
			'googlepay' => $this->language->get('text_spm_googlepay'),
			'applepay' => $this->language->get('text_spm_applepay'),
		);

		$settings = array(
			'paymentname'             => '',
			'merchant'                => '',
			'secret_key'              => '',
			'test_secret_key'         => '',
			'mode'                    => 'test',
			'success_url'             => $this->catalogLink('checkout/success'),
			'fail_url'                => $this->catalogLink('checkout/fail'),
			'back_url'                => $this->catalogLink('index.php?route=checkout/checkout'),
			'sno'                     => 'usn_income_outcome',
			'product_vat'             => 'none',
			'delivery_vat'            => 'none',
			'voucher_vat'             => 'none',
			'payment_method'          => 'full_prepayment',
			'payment_object'          => 'commodity',
			'payment_object_delivery' => 'service',
			'payment_object_voucher'  => 'commodity',
			'logging'                 => 0,
			'total'                   => '',
			'order_status_id'         => '5', //Complete
			'refund_order_status_id'  => '11', //Refunded
			'confirm_order_status_id' => '5', //Complete
			'geo_zone_id'             => 0,
			'status'                  => 0,
			'sort_order'              => '',
			'log_size_limit'          => 10,
			'preauth'                 => 0,
			'pm_checkbox'             => 0,
			'show_payment_methods'    => ['sbp', 'card', 'googlepay', 'applepay'],
		);

		$data['text_pm_checkbox_tooltip'] = $this->language->get('text_pm_checkbox_tooltip');

		foreach ($settings as $key => $default) {
			$data['entry_' . $key] = $this->language->get('entry_' . $key);

			if (isset($this->request->post['modulbank_' . $key])) {
				$data['modulbank_' . $key] = $this->request->post['modulbank_' . $key];
			} elseif ($this->config->has('modulbank_' . $key)) {
				$data['modulbank_' . $key] = $this->config->get('modulbank_' . $key);
			} else {
				$data['modulbank_' . $key] = $default;
			}
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		//$data['header']      = $this->load->controller('common/header');
		//$data['column_left'] = $this->load->controller('common/column_left');
		//$data['footer']      = $this->load->controller('common/footer');

		//$this->response->setOutput($this->load->view('payment/modulbank.tpl', $data));
		$this->data=$data;
		$this->template = 'payment/modulbank.tpl';
		$this->children = array(
			'common/header',
			#'common/column_left',
			'common/footer',
		);

		$this->response->setOutput($this->render());			
	}

	protected function validate()
	{
		if (!$this->user->hasPermission('modify', 'payment/modulbank')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['modulbank_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['modulbank_paymentname']) {
			$this->error['paymentname'] = $this->language->get('error_paymentname');
		}

		return !$this->error;
	}

	public function logs()
	{
		try {
			ModulbankHelper::sendPackedLogs(DIR_LOGS);
		} catch (Exception $e) {
			echo $e->getMessage();
			return;
		}
	}

	private function catalogLink($route)
	{
		return str_replace(HTTPS_SERVER, HTTPS_CATALOG, $this->url->link($route, '', true));
	}

	public function install()
	{
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "modulbank` (
				order_id		INT(11)  NOT NULL,
				amount			DECIMAL(13,2) NOT NULL,
				transaction		VARCHAR(32) NULL,
				PRIMARY KEY (order_id)
		)");

	}

	public function uninstall()
	{
		$this->db->query(' drop table IF EXISTS `' . DB_PREFIX . 'modulbank`');
	}
}
