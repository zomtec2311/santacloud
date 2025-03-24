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

/** @var $l \OCP\IL10N */
/** @var $_ array */


script('santacloud', 'santacloud-adminsettings');
?>
<form id="mySetting" class="sub-section">
  <h3><?php p($l->t("SantaCloud Settings")); ?></h3>
    <p>
        <input class="wtpara_test" type="radio" name="wtpara_test" id="wtpara_test1" value="1" <?php if ($_['wtpara_test'] == 1) { echo 'checked'; } else { echo ''; } ?>>
        <label class="wtpara_test" for="wtpara_test"><?php p($l->t("Test mode ON")); ?></label>
        <input class="wtpara_test" type="radio" name="wtpara_test" id="wtpara_test2" value="2" <?php if ($_['wtpara_test'] == 2) { echo 'checked'; } else { echo ''; } ?>>
        <label class="wtpara_test" for="wtpara_test"><?php p($l->t("Test mode OFF")); ?></label>
    </p>
        <output for="wtpara_test">
          <p style="width:300px;font-size: 0.8em;"><?php p($l->t("Test yes or no. If the test is allowed, the setting for past days is ignored. In the Testmdus you can open all doors and an additional notice is displayed.")); ?>
            <br><b><?php p($l->t("If Test mode is ON, the Lock mode should also be ON. Otherwise users also can see the content of the doors while you are testing.")); ?></b>
          </p>
        </output>
    <p>
        <input class="wtpara_lock" type="radio" name="wtpara_lock" id="wtpara_lock1" value="1" <?php if ($_['wtpara_lock'] == 1) { echo 'checked'; } else { echo ''; } ?>>
        <label class="wtpara_lock" for="wtpara_lock"><?php p($l->t("Lock mode ON")); ?></label>
        <input class="wtpara_lock" type="radio" name="wtpara_lock" id="wtpara_lock2" value="2" <?php if ($_['wtpara_lock'] == 2) { echo 'checked'; } else { echo ''; } ?>>
        <label class="wtpara_lock" for="wtpara_lock"><?php p($l->t("Lock mode OFF")); ?></label>
    </p>
        <output for="wtpara_lock">
          <p style="width:300px;font-size: 0.8em;">
            <?php p($l->t("Lock mode yes or no. Lock mode ON means, that users are directed to a please-wait page. OFF means, that users are allowed to see the calendar.")); ?>
          </p>
        </output>
    <p>
        <input class="wtpara_last" type="radio" name="wtpara_last" id="wtpara_last1" value="1" <?php if ($_['wtpara_last'] == 1) { echo 'checked'; } else { echo ''; } ?>>
        <label class="wtpara_last" for="wtpara_last"><?php p($l->t("Yes")); ?></label>
        <input class="wtpara_last" type="radio" name="wtpara_last" id="wtpara_last2" value="2" <?php if ($_['wtpara_last'] == 2) { echo 'checked'; } else { echo ''; } ?>>
        <label class="wtpara_last" for="wtpara_last"><?php p($l->t("No")); ?></label>
    </p>
        <output for="wtpara_last">
          <p style="width:300px;font-size: 0.8em;">
            <?php p($l->t("Allow to open doors for past days")); ?>
          </p>
        </output>

</form>
