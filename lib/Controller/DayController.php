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
use OCP\IL10N;
use OCP\IConfig;
use OCP\IRequest;
use OCP\AppFramework\Services\IInitialState;
use OCA\LogReader\Service\SettingsService;

class DayController extends Controller {

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
     private IInitialState $initialState,
     private SettingsService $settingsService
   ) {
     parent::__construct('santacloud', $request);
     $this->l = $l;
     $this->config = $config;
   }

   public function setSettingTest($wtpara_test) {
     $this->config->setAppValue('santacloud', 'wtpara_test', $wtpara_test);
 		 return;
 	 }

   public function setSettingLast($wtpara_last) {
     $this->config->setAppValue('santacloud', 'wtpara_last', $wtpara_last);
     return;
   }

   public function getxml() {
     $wtpara_test = (int)$this->config->getAppValue('santacloud', 'wtpara_test');
 		 if (!isset($wtpara_test)) {
 		    $wtpara_test = 1;
        $this->config->setAppValue('santacloud', 'wtpara_test', 1);
 		 }
     $wtpara_last = (int)$this->config->getAppValue('santacloud', 'wtpara_last');
  	 if (!isset($wtpara_last)) {
       $wtpara_last = 2;
       $this->config->setAppValue('santacloud', 'wtpara_last', 2);
  	 }
     $wtdayfile = __DIR__ . '/../../data/days.xml';
     if (!file_exists($wtdayfile)) {
       return $this->l->t('No days.xml file found. Instructions in README: Go to /apps/santacloud/data/ then copy days_example.xml and rename the copy to days.xml, edit and store days.xml!!');
     }
     else { return; }
 	 }

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
         $datexml  = $xml->days->day[$day-1]->date;
         $pieces = explode("-", $datexml);
         $xmlmonth = intval($pieces[1]);
         if ($xmlmonth !== $thismonth) { return; }
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
 }
