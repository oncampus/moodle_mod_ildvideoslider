<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    mod_ildvideoslider
 * @copyright  2016 Fachhochschule Lübeck ILD
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * @param $feature
 * @return bool|null
 */
function ildvideoslider_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        case FEATURE_COMPLETION_HAS_RULES:
            return false;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        default:
            return null;
    }
}

/**
 * @param $data
 * @return mixed
 */
function ildvideoslider_add_instance($data) {
    global $DB;

    $data->timemodified = time();
    $data->id = $DB->insert_record('ildvideoslider', $data);

    return $data->id;
}

/**
 * @param $data
 * @return bool
 */
function ildvideoslider_update_instance($data) {
    global $DB;

    $data->timemodified = time();
    $data->id = $data->instance;

    $DB->update_record('ildvideoslider', $data);

    return true;
}

/**
 * @param $id
 * @return bool
 */
function ildvideoslider_delete_instance($id) {
    global $DB;

    if (!$ildvideoslider = $DB->get_record('ildvideoslider', array('id' => $id))) {
        return false;
    }

    $DB->delete_records('ildvideoslider', array('id' => $ildvideoslider->id));

    return true;
}

/**
 * @param cm_info $cm
 */
function ildvideoslider_cm_info_dynamic(cm_info $cm) {
    global $USER;

    if ($USER->editing != 1) {
        $cm->set_no_view_link();
    }
}

function ildvideoslider_cm_info_view(cm_info $cm) {
    global $USER, $DB, $PAGE, $CFG;

    if ($USER->editing != 1) {
        $PAGE->requires->js(new moodle_url($CFG->httpswwwroot . '/mod/ildvideoslider/js/ildvideoslider.js'));
        $cmr = $cm->get_course_module_record();

        $ildvideoslider = $DB->get_record('ildvideoslider', array('id' => $cmr->instance));
        $videos = $ildvideoslider->videos;
        $videoArray = explode("\r\n", $videos);

        $output = ' <div class="video-slides"></div>
	                    <div class="video-control">
		                    <div class="video-prev"><p>&#10094;</p></div>
		                    <div class="video-titles-outter"><ul class="video-titles-inner" ></ul></div>
		                    <div class="video-next"><p>&#10095;</p></div>
	                    </div>';

        $jsOutput = '<script>';
        $jsOutput .= 'var videos = [';
        foreach ($videoArray as $video) {
            $videoDetails = explode('|', $video);

            $jsOutput .= '{title_1:"' . trim($videoDetails[0]) . '", title_2:"' . trim($videoDetails[1]) . '", id:"' . trim($videoDetails[2]) . '"},';
        }
        $jsOutput .= '];';
        $jsOutput .= '</script>';

        $output .= $jsOutput;
        $cm->set_content($output);
    }
}