=== Statify – Extended Evaluation ===
Contributors: patrickrobrecht
Tags: stats, analytics, privacy, statistics
Requires at least: 4.4
Tested up to: 4.9
Requires PHP: 5.6
Stable tag: 2.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin evaluates the data collected with the privacy-friendly Statify Plugin (data tables and diagrams). The evaluation can be downloaded as csv.


== Description ==
This plugin evaluates the data collected with the privacy-friendly Statify Plugin which is only saving date, referrer and target url for every page view.

The plugin creates evaluations for the following criteria:

* views per year / month / day
* most popular content
* views per post
* views per referrer

The results are shown in data tables and diagrams. The evaluation results can be downloaded

* as CSV files (for an import into LibreOffice Calc or Microsoft Excel)
* as PNG, JPG or SVG image or PDF document (all diagrams) using the [Highcharts](http://www.highcharts.com/) JavaScript library

= Requirements =
* [Statify](https://wordpress.org/plugins/statify/) must be installed and activated
* PHP 5.6 or greater


== Frequently Asked Questions ==

= I have installed Statify already. Why should I install this plugin? =

This plugin provides detailed statistics for the data collected with the Statify plugin.

If you are interested in a deeper analysis of that data or want to export the evaluation as csv file, this is the right plugin for you.

= I've just installed the plugin, but I don't see any evaluation. =

If you've just installed Statify, no data is stored in the database which can be evaluated. Visit the statistics pages later again.

= How can I download the evaluation results? =

The complete data can be downloaded by clicking the *Export* button next to the headline of each evaluation.

The diagrams can be downloaded by clicking the menu in the upper right corner of the diagram and choosing one of the download options (available: PNG image, JPG image, PDF document, SVG image).

= How do I give other users access to the *Statify – Extended Evaluation* pages? =

Per default, administrators have access to the *Statify – Extended Evaluation* statistics.

You can give users of other roles the capability to see the *Statify – Extended Evaluation* pages by extending their capabilities with a member plugin (e. g. [Members](https://wordpress.org/plugins/members/)).

Therefore you'll have to add the *see_statify_evaluation* capability to the user role.


== Screenshots ==

1. Monthly / yearly views
2. Most popular content statistics (for the whole time Statify data were stored)
3. Referrer statistics (for the given period of time)


== Changelog ==

Please see [the changelog at GitHub](https://github.com/patrickrobrecht/extended-evaluation-for-statify/blob/master/CHANGELOG.md) for the details.

= 2.4 =
* Feature: Selection of predefined time periods
* Enhancement: Better selection of posts
* Enhancement: Minified CSS/JS files, build command
* Enhancement: Check for compliance with WordPress Coding Guidelines with PHP_CodeSniffer
* Refactoring the charts code

= 2.3.1 =
* Bugfix: diagram for yearly views

= 2.3 =
* Bugfix: Remove buggy Statify Analyst user role. Use [Members](https://wordpress.org/plugins/members/) to add the capability *see_statify_evaluation* to other roles than administrator.
* Bugfix: proper escaping for all outputs
* Enhancement: Better conformance to WordPress Coding Guidelines
* Enhancement: Rename the plugin to *Statify – Extended Evaluation*

= 2.2 =
* Enhancement: Minor changes in the style
* Enhancement: If there is nothing to display, show "no data available"

= 2.1 =
* Enhancement: URLs in csv export
* Enhancement: percentages on Content and Referrers pages


== Upgrade Notice ==

= 2.4 =
New features and enhancements. It is recommended for all users.
