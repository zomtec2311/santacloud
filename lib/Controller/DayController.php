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
   public function __construct(
     IL10N $l,
     IConfig $config,
     IRequest $request,
     private IInitialState $initialState
     //private SettingsService $settingsService
   ) {
     parent::__construct('santacloud', $request);
     $this->l = $l;
     $this->config = $config;
     $this->request = $request;
   }

   public function getParam($who) {
     return $this->config->getAppValue('santacloud', $who);
   }

   public function setParam($who,$wert) {
     $this->config->setAppValue('santacloud', $who, $wert);
 		 return;
 	 }

   #[NoAdminRequired]
   public function getxml() {
     $wtpara_test = (int)$this->config->getAppValue('santacloud', 'wtpara_test');
     if (!isset($wtpara_test) or ($wtpara_test === 0)) {
 			 $wtpara_test = 1;
 			 $this->config->setAppValue('santacloud', 'wtpara_test', 1);
 		}
 		$wtpara_last = (int)$this->config->getAppValue('santacloud', 'wtpara_last');
 		if (!isset($wtpara_last) or ($wtpara_last === 0)) {
 			 $wtpara_last = 2;
 			 $this->config->setAppValue('santacloud', 'wtpara_last', 2);
 		}
    $wtpara_lock = (int)$this->config->getAppValue('santacloud', 'wtpara_lock');
 		if (!isset($wtpara_lock) or ($wtpara_lock === 0)) {
 			 $wtpara_lock = 1;
 			 $this->config->setAppValue('santacloud', 'wtpara_lock', 1);
 		}
     $wtdayfile = __DIR__ . '/../../data/days.xml';
     if (!file_exists($wtdayfile)) {
       $file = __DIR__ . '/../../data/days_example.xml';
       if (!copy($file, $wtdayfile)) {
         return "failed to copy $file... " . $this->l->t('No days.xml file found. Instructions in README: Go to /apps/santacloud/data/ then copy days_example.xml and rename the copy to days.xml, edit and store days.xml!!');
       }
       return $this->l->t('No days.xml file found. Instructions in README: Go to /apps/santacloud/data/ then copy days_example.xml and rename the copy to days.xml, edit and store days.xml!!');
     }
     else { return; }
 	 }

   public function xmlcontent() {
     $wtdayfile = __DIR__ . '/../../data/days.xml';
     $out = '';
     $arr = array();
     if (!file_exists($wtdayfile)) {
       $file = __DIR__ . '/../../data/days_example.xml';
       if (!copy($file, $wtdayfile)) {
         return "failed to copy $file... " . $this->l->t('No days.xml file found. Instructions in README: Go to /apps/santacloud/data/ then copy days_example.xml and rename the copy to days.xml, edit and store days.xml!!');
       }
       return $this->l->t('No days.xml file found. Instructions in README: Go to /apps/santacloud/data/ then copy days_example.xml and rename the copy to days.xml, edit and store days.xml!!');
     }
     else {
       $xmlStr = file_get_contents($wtdayfile);
       $xml = simplexml_load_string($xmlStr);
       for ($i = 0; $i <= 23; $i++) {
         $xml->days->day[$i]->title = strval($xml->days->day[$i]->title);
         $xml->days->day[$i]->description = strval($xml->days->day[$i]->description);
       }
       return $xml->days;
     }
    }

    public function dayxmlcontent($day) {
      $day = intval($day);
      $wtdayfile = __DIR__ . '/../../data/days.xml';
      $out = '';
      $arr = array();
      if (!file_exists($wtdayfile)) {
        $file = __DIR__ . '/../../data/days_example.xml';
        if (!copy($file, $wtdayfile)) {
          return "failed to copy $file... " . $this->l->t('No days.xml file found. Instructions in README: Go to /apps/santacloud/data/ then copy days_example.xml and rename the copy to days.xml, edit and store days.xml!!');
        }
        return $this->l->t('No days.xml file found. Instructions in README: Go to /apps/santacloud/data/ then copy days_example.xml and rename the copy to days.xml, edit and store days.xml!!');
      }
      else {
        $xmlStr = file_get_contents($wtdayfile);
        $xml = simplexml_load_string($xmlStr);
        $obja = new \stdClass();
        $obja->day = $day;
        $obja->date = strval($xml->days->day[$day-1]->date);
        $obja->title = strval($xml->days->day[$day-1]->title);
        $obja->description = strval($xml->days->day[$day-1]->description);
        return $obja;
      }
     }

     public function savedayxmlcontent($day, $date, $title, $description): JSONResponse {
       $wtdayfile = __DIR__ . '/../../data/days.xml';
       //$title = htmlspecialchars_decode($title);
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
     $wtdayfile = __DIR__ . '/../../data/days.xml';
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
     $wtdayfile = __DIR__ . '/../../data/days.xml';
       if (!file_exists($wtdayfile)) { return; }
       else {
         $xmlStr = file_get_contents($wtdayfile);
         $xml = simplexml_load_string($xmlStr);
         $out .= '<h1 style="font-size: 1.3em;">' . $xml->days->day[$day-1]->title . '</h1>';
         $out .= '<br>' . $xml->days->day[$day-1]->description;
         return $out;
       }
   }
 }
