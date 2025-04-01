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
return [
  'routes' => [
     ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
     ['name' => 'Day#getxml', 'url' => '/getxml', 'verb' => 'GET'],
     ['name' => 'Day#getday', 'url' => '/day/{day}', 'verb' => 'GET'],
     ['name' => 'Day#previewday', 'url' => '/previewday/{day}', 'verb' => 'GET'],
     ['name' => 'Day#setParam', 'url' => '/ajax/{who}/{wert}', 'verb' => 'GET'],
     ['name' => 'Day#xmlcontent', 'url' => '/ajax/xmlcontent', 'verb' => 'GET'],
     ['name' => 'Day#dayxmlcontent', 'url' => '/editday/{day}', 'verb' => 'GET'],
     ['name' => 'Day#savedayxmlcontent', 'url' => '/saveday', 'verb' => 'POST'],
     ['name' => 'Day#getParam', 'url' => '/getparam/{who}', 'verb' => 'GET'],
  ]
];
