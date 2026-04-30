<?php
/**
 *
 * SantaCloud APP (Nextcloud)
 *
 * @author Wolfgang Tödt <wtoedt@gmail.com>
 *
 * @copyright Copyright (c) 2025 Wolfgang Tödt
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
declare(strict_types=1);

namespace OCA\SantaCloud\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Response;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IL10N;
use OCP\IConfig;
use OCP\IRequest;
use OCP\AppFramework\Services\IInitialState;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\OpenAPI;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\Attribute\AuthorizedAdminSetting;
use OCP\Files\IAppData;
use OCP\Files\SimpleFS\ISimpleFile;
use OCP\Files\SimpleFS\ISimpleFolder;
use Psr\Log\LoggerInterface;

class DayController extends Controller {
  #[NoCSRFRequired]
  #[NoAdminRequired]
  #[OpenAPI(OpenAPI::SCOPE_IGNORE)]
  #[FrontpageRoute(verb: 'POST', url: '/')]
	/**
	 * @param string $query
	 * @param int $count
	 * @param int $offset
	 * @return JSONResponse
	 */
   /** @var IL10N */
   private $config;
   private $l;
   private $appData;
   private $logger;
   public function __construct(
     IL10N $l,
     IConfig $config,
     IRequest $request,
     IAppData $appData,
     LoggerInterface $logger,
     private IInitialState $initialState
   ) {
     parent::__construct('santacloud', $request);
     $this->l = $l;
     $this->config = $config;
     $this->request = $request;
     $this->appData = $appData;
     $this->logger = $logger;
   }

   public function getParam($who): DataResponse {
     return new DataResponse([
                'value' => $this->config->getAppValue('santacloud', $who),
            ]);
   }
   
   #[AuthorizedAdminSetting(settings: Admin::class)]
   public function setParam($who,$wert) {
     $this->config->setAppValue('santacloud', $who, $wert);
 		 return;
 	 }

   public function saveowntext($text) {
     $this->config->setAppValue('santacloud', 'owntext', $text);
 		 return;
 	 }
 	 
  public function xmlcontent(): DataResponse {
     $this->checkdatafolder();
     $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
     $out = '';
     $arr = array();
       $xmlStr = file_get_contents($wtdayfile);
       $xml = simplexml_load_string($xmlStr);
       for ($i = 0; $i <= 23; $i++) {
         $xml->days->day[$i]->title = strval($xml->days->day[$i]->title);
         $xml->days->day[$i]->description = strval($xml->days->day[$i]->description);
       }
       $xmlcontent = $xml->days;
     return new DataResponse([
								'xmlcontent' => $xmlcontent,
            ]);
    }

    public function dayxmlcontent($day) {
      $day = intval($day);
      $this->checkdatafolder();
      $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
      $out = '';
      $arr = array();
        $xmlStr = file_get_contents($wtdayfile);
        $xml = simplexml_load_string($xmlStr);
        $obja = new \stdClass();
        $obja->day = $day;
        $obja->date = strval($xml->days->day[$day-1]->date);
        $obja->title = strval($xml->days->day[$day-1]->title);
        $obja->description = strval($xml->days->day[$day-1]->description);
        return $obja;
     }

     public function savedayxmlcontent($day, $date, $title, $description): JSONResponse {
       $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
       $xmlStr = file_get_contents($wtdayfile);
       $xml = simplexml_load_string($xmlStr);
       $xml->days->day[$day-1]->date = $date;
       $xml->days->day[$day-1]->title = '<![CDATA[' . $title . ']]>';
       $xml->days->day[$day-1]->description = '<![CDATA[' . $description . ']]>';
       file_put_contents($wtdayfile, html_entity_decode($xml->asXML()),LOCK_EX);
       return new JSONResponse([
         'day' => $day,
         'date' => $date,
         'title' => $title,
        'description' => $description,
		   ]);
     }

   #[NoAdminRequired]
   public function getday(string $day) {
     $wtpara_test = (int)$this->config->getAppValue('santacloud', 'wtpara_test');
     $wtpara_last = (int)$this->config->getAppValue('santacloud', 'wtpara_last');
     $day = intval($day);
     $today = intval(date("j"));
     $thismonth = intval(date("n"));
     $out = "";
     $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
     if( $wtpara_test === 1) {
       if (!file_exists($wtdayfile)) { return; }
       else {
         $xmlStr = file_get_contents($wtdayfile);
         $xml = simplexml_load_string($xmlStr);
         $out .= '<br><h1 style="font-size: 2em;color:red;">' . $this->l->t('Attention - test mode is ON') . '</h1><br><h1 style="font-size: 1.3em;">' . $xml->days->day[$day-1]->title . '</h1>';
         $out .= '<br>' . $xml->days->day[$day-1]->description;
         return $out;
       }
     }
     else {
       if ($day > $today) { return '<br><b>' . $this->l->t('Unfortunately, you are too early, because you are only allowed to open this door on the right day.') . '</b>'; }
       if (!file_exists($wtdayfile)) {
         return;
       }
       else {
         $xmlStr = file_get_contents($wtdayfile);
         $xml = simplexml_load_string($xmlStr);
         $datexml  = (string) $xml->days->day[$day-1]->date[0];
         $pieces = explode("-", $datexml);
         $xmlmonth = intval($pieces[1]);
         if ($xmlmonth !== $thismonth) { return $this->l->t('Unfortunately, you are too early, because you are only allowed to open this door on the right day.'); }
         if ( $day === $today ) {
           $out .= '<br><h1 style="font-size: 1.3em;">' . $xml->days->day[$day-1]->title . '</h1>';
           $out .= '<br>' . $xml->days->day[$day-1]->description;
           return $out;
         }
         if ( ($day < $today) and ($wtpara_last === 2)) { return '<br><b>' . $this->l->t('Unfortunately, you are too late, because this door is no longer available.') . '</b>'; }
         else {
           $out .= '<br><h1 style="font-size: 1.3em;">' . $xml->days->day[$day-1]->title . '</h1>';
           $out .= '<br>' . $xml->days->day[$day-1]->description;
           return $out;
         }
       }
     }
   }

   public function previewday(string $day) {
     $day = intval($day);
     $out = "";
     $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
       if (!file_exists($wtdayfile)) { return; }
       else {
         $xmlStr = file_get_contents($wtdayfile);
         $xml = simplexml_load_string($xmlStr);
         $out .= '<h1 style="font-size: 1.3em;">' . $xml->days->day[$day-1]->title . '</h1>';
         $out .= '<br>' . $xml->days->day[$day-1]->description;
         return $out;
       }
   }
 
  public function checkdatafolder() {
        $appdataroot = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud';
 		if (!is_dir($appdataroot . '/img')) $this->appData->newFolder('img');
        if (!is_dir($appdataroot . '/backgroundimg')) $this->appData->newFolder('backgroundimg');
        if (!is_dir($appdataroot . '/xml')) $this->appData->newFolder('xml');
 		
 		$wtdayfile = $this->config->getSystemValue('datadirectory') . '/days.xml';
        $newwtdayfile = $appdataroot . '/xml/days.xml';
        $file = __DIR__ . '/../../data/days_example.xml';
        $backgroundimg = __DIR__ . '/../../img/background.jpg';
        $newbackgroundimg = $appdataroot . '/backgroundimg/background.jpg';
        $exampleimg = __DIR__ . '/../../data/img/xmas-cookies.png';
        $newexampleimg = $appdataroot . '/img/xmas-cookies.png';
        
        if (!file_exists($wtdayfile) AND !$this->appData->getFolder('xml')->fileExists('days.xml')) {
          if (!copy($file, $newwtdayfile)) {
            $this->logger->warning("SantaCloud: failed to copy $file... ");
          }
          else {
            $this->logger->debug("SantaCloud: success copy $file... ");
          }
        }
        elseif (file_exists($wtdayfile) AND !$this->appData->getFolder('xml')->fileExists('days.xml')) {
          if (!rename($wtdayfile, $newwtdayfile)) {
              $this->logger->warning("SantaCloud: failed to move existing $wtdayfile... ");
          }
          else {
            $this->logger->debug("SantaCloud: success moving $wtdayfile... ");
          }
        }
        if (!$this->appData->getFolder('backgroundimg')->fileExists('background.jpg')) {
          if (!copy($backgroundimg, $newbackgroundimg)) {
            $this->logger->warning("SantaCloud: failed to copy $backgroundimg... ");
          }
          else {
            $this->logger->debug("SantaCloud: success copy $backgroundimg... ");
          }
        }
        if (!$this->appData->getFolder('img')->fileExists('xmas-cookies.png')) {          
          if (!copy($exampleimg, $newexampleimg)) {
            $this->logger->warning("SantaCloud: failed to copy $exampleimg... ");
          }
          else {
            $this->logger->debug("SantaCloud: success copy $exampleimg... ");
          }
        }                
         return;
       }
   }
