<?php
namespace OCA\SantaCloud\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\Settings\ISettings;

class SantaCloudAdmin implements ISettings {
    private IL10N $l;
    private IConfig $config;

    public function __construct(IConfig $config, IL10N $l) {
        $this->config = $config;
        $this->l = $l;
    }

    /**
     * @return TemplateResponse
     */
    public function getForm() {
      /*
        $parameters = [
            'mySetting' => $this->config->getSystemValue('santacloud', true),
        ];
        */
        //$sample_wt_zeilen = $this->config->getAppValue('sample', 'sample_wt_zeilen', '');
        $wtpara_test = $this->config->getAppValue('santacloud', 'wtpara_test', '');
        $wtpara_last = $this->config->getAppValue('santacloud', 'wtpara_last', '');
    		$parameters = [
          //'sample_wt_zeilen' => $sample_wt_zeilen,
          'wtpara_test' => $wtpara_test,
          'wtpara_last' => $wtpara_last
    		];

        return new TemplateResponse('santacloud', 'settings/admin', $parameters, '');
        /*
        TemplateResponse {
      		return new TemplateResponse(
      			Application::APP_ID,
      			'index',
      		);
      	}
        */
    }

    public function kgetForm() {


  		$sample_wt_zeilen = $this->config->getAppValue('sample', 'sample_wt_zeilen', '');
  		$sample_wt_art = $this->config->getAppValue('sample', 'sample_wt_art', '');
  		$parameters = [
  			'sample_wt_zeilen' => $sample_wt_zeilen,
  			'sample_wt_art' => $sample_wt_art
  		];

  		return new TemplateResponse('sample', 'admin_settings', $parameters, '');
  	}

    public function getSection() {
        return 'santacloud'; // Name of the previously created section.
    }

    /**
     * @return int whether the form should be rather on the top or bottom of
     * the admin section. The forms are arranged in ascending order of the
     * priority values. It is required to return a value between 0 and 100.
     *
     * E.g.: 70
     */
    public function getPriority() {
        return 10;
    }
}
