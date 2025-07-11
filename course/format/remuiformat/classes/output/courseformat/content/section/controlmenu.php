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
 * Contains the default section controls output class.
 *
 * @package   format_remuiformat
 * @copyright 2020 Ferran Recio <ferran@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_remuiformat\output\courseformat\content\section;

use context_course;
use core_courseformat\output\local\content\section\controlmenu as controlmenu_base;
use core\output\action_menu\link as action_menu_link;
use core\output\action_menu\link_secondary as action_menu_link_secondary;
use core\output\pix_icon;

/**
 * Base class to render a course section menu.
 *
 * @package   format_remuiformat
 * @copyright 2020 Ferran Recio <ferran@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class controlmenu extends controlmenu_base {

    /** @var course_format the course format class */
    protected $format;

    /** @var section_info the course section class */
    protected $section;

    /**
     * Generate the edit control items of a section.
     *
     * @return array of edit control items
     */
    public function section_control_items() {
        global $CFG;
        $format = $this->format;
        $section = $this->section;
        $course = $format->get_course();
        $courseformatdatacommontrait = \format_remuiformat\course_format_data_common_trait::getinstance();
        $sectionreturn = $courseformatdatacommontrait->edw_get_section_num($format);

        $coursecontext = context_course::instance($course->id);

        if ($sectionreturn) {
            $url = course_get_url($course, $section->section);
        } else {
            $url = course_get_url($course);
        }
        $url->param('sesskey', sesskey());

        $controls = [];
        if ($section->section && has_capability('moodle/course:setcurrentsection', $coursecontext)) {
            if ($course->marker == $section->section) {  // Show the "light globe" on/off.
                $url->param('marker', 0);
                $highlightoff = get_string('highlightoff');
                if($CFG->branch >= 500) {
                    $controls['highlight'] = new \action_menu_link_secondary(
                        $url,
                        new \pix_icon('i/marked', ''),
                        $highlightoff,
                        [
                            'class' => 'editing_highlight',
                            'data-action' => 'sectionUnhighlight'
                        ]
                    );
                } else {
                    $controls['highlight'] = [
                        'url' => $url,
                        'icon' => 'i/marked',
                        'name' => $highlightoff,
                        'pixattr' => ['class' => ''],
                        'attr' => [
                            'class' => 'editing_highlight',
                            'data-action' => 'sectionUnhighlight'
                        ],
                    ];
                }
            } else {
                $url->param('marker', $section->section);
                $highlight = get_string('highlight');
                if($CFG->branch >= 500) {
                    $controls['highlight'] = new \action_menu_link_secondary(
                        $url,
                        new \pix_icon('i/marker', ''),
                        $highlight,
                        [
                            'class' => 'editing_highlight',
                            'data-action' => 'sectionHighlight'
                        ]
                    );
                } else {
                    $controls['highlight'] = [
                        'url' => $url,
                        'icon' => 'i/marker',
                        'name' => $highlight,
                        'pixattr' => ['class' => ''],
                        'attr' => [
                            'class' => 'editing_highlight',
                            'data-action' => 'sectionHighlight'
                        ],
                    ];
                }
            }
        }

        $parentcontrols = parent::section_control_items();

        // If the edit key exists, we are going to insert our controls after it.
        if (array_key_exists("edit", $parentcontrols)) {
            $merged = [];
            // We can't use splice because we are using associative arrays.
            // Step through the array and merge the arrays.
            foreach ($parentcontrols as $key => $action) {
                $merged[$key] = $action;
                if ($key == "edit") {
                    // If we have come to the edit key, merge these controls here.
                    $merged = array_merge($merged, $controls);
                }
            }

            return $merged;
        } else {
            return array_merge($controls, $parentcontrols);
        }
    }
}
