<?php
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;

	/**
	 * Exports the evaluation data to a csv file.
	 * 
	 * @param array $parameters the parameters for the export
	 */
	function eefstatify_export_as_csv( $parameters ) {
		if ( !isset( $parameters['export'] ) ) {
			_e( 'No valid export parameters.', 'extended-evaluation-for-statify' );
			return;
		}
		
		$export = sanitize_text_field( $parameters['export'] );
		switch ( $export ) {
			// exports from the plugin's main page
			case 'daily':
				if ( isset( $parameters['year'] ) && strlen( $parameters['year'] ) == 4 ) {
					$year = intval( sanitize_text_field( $parameters['year'] ) );
					eefstatify_generate_csv_file( 'daily' . '-' . $year, eefstatify_get_daily_data_for_csv( $year ) );
				} else {
					_e( 'No valid export parameters.', 'extended-evaluation-for-statify' );
					return;
				}
				break;
			case 'monthly':
				eefstatify_generate_csv_file( 'monthly', eefstatify_get_monthly_data_for_csv() );
				break;
			
			// exports from the content page
			case 'content':
				eefstatify_generate_csv_file( 'content', eefstatify_get_content_data_for_csv() );
				break;
			case 'content-date-period':
				$start = isset( $_POST['start'] ) ? $_POST['start'] : '';
				$end = isset( $_POST['end'] ) ? $_POST['end'] : '';
				if ( eefstatify_is_valid_date_string( $start ) && eefstatify_is_valid_date_string( $end ) ) {
					eefstatify_generate_csv_file( 'content-date-period-from-' . $start . '-to-' . $end, 
							eefstatify_get_content_data_for_csv( $start, $end ) );
				} else {
					_e( 'No valid export parameters.', 'extended-evaluation-for-statify' );
					return;
				}
				break;
			case 'posttype':
				$post_type = isset( $parameters['posttype'] ) ? sanitize_text_field( $parameters['posttype'] ) : 'post';
				if ( !in_array( $post_type, eefstatify_get_post_types() ) ) {
					_e( 'No valid export parameters.', 'extended-evaluation-for-statify' );
					return;
				}	
				eefstatify_generate_csv_file( $post_type, eefstatify_get_post_type_content_data_for_csv( $post_type ) );
				break;
			case 'posttype-date-period':
				$post_type = isset( $parameters['posttype'] ) ? sanitize_text_field( $parameters['posttype'] ) : 'post';
				if ( !in_array( $post_type, eefstatify_get_post_types() ) ) {
					_e( 'No valid export parameters.', 'extended-evaluation-for-statify' );
					return;
				}
				$start = isset( $_POST['start'] ) ? $_POST['start'] : '';
				$end = isset( $_POST['end'] ) ? $_POST['end'] : '';
				if ( eefstatify_is_valid_date_string( $start ) && eefstatify_is_valid_date_string( $end ) ) {
					eefstatify_generate_csv_file( $post_type . '-date-period-from-' . $start . '-to-' . $end,
							eefstatify_get_post_type_content_data_for_csv( $post_type, $start, $end ) );
				} else {
					_e( 'No valid export parameters.', 'extended-evaluation-for-statify' );
					return;
				}
				break;
			
			// exports from the referrer page
			case 'referrer':
				eefstatify_generate_csv_file( 'referrer', eefstatify_get_referrer_data_for_csv() );
				break;
			case 'referrer-date-period':
				$start = isset( $_POST['start'] ) ? $_POST['start'] : '';
				$end = isset( $_POST['end'] ) ? $_POST['end'] : '';
				if ( eefstatify_is_valid_date_string( $start ) && eefstatify_is_valid_date_string( $end ) ) {
					eefstatify_generate_csv_file( 'referrer-date-period-from-' . $start . '-to-' . $end, 
							eefstatify_get_referrer_data_for_csv( $start, $end ) );
				} else {
					_e( 'No valid export parameters.', 'extended-evaluation-for-statify' );
					return;
				}
				break;
			default:
				_e( 'No valid export parameters.', 'extended-evaluation-for-statify' );
				break;
		}
	}

	/**
	 * Generates the csv file with the given data.
	 * 
	 * @param unknown $export_name the name for the export file
	 * @param unknown $data the data as an array of array of string
	 */
	function eefstatify_generate_csv_file( $export_name, $data ) {
		$export_filename = eefstatify_get_filename( $export_name );
		
		$content = eefstatify_create_csv( $data );
		$bytes = strlen( $content );
		$charset = 'UTF-8';
		
		// Set header data making browsers downloading the csv file.
		header( 'Pragma: public' );
		header( 'Expires: 0' );
		header( 'Cache-Control: private', FALSE );
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Content-Length: ' . $bytes );
		header( 'Content-Type: text/csv; charset=' . $charset );
		header( 'Content-Disposition: attachment; filename="' . $export_filename . '.csv";' );
		
		echo $content;
	}
	
	/**
	 * Returns the daily data for the csv file, including headlines.
	 * 
	 * @param unknown $year the year to export
	 * @return multitype: an array of arrays (1st dimension: day, 2nd dimension: month)
	 */
	function eefstatify_get_daily_data_for_csv( $year ) {
		$days = eefstatify_get_days();
		$months = eefstatify_get_months();
		
		$result = array();
		$headline = array();
		array_push( $headline, __( 'Year', 'extended-evaluation-for-statify' ) . ' ' . $year );
		foreach ( $months as $month ) {
			array_push( $headline, eefstatify_get_month_name( $month ) );
		}
		array_push( $result, $headline );
		
		$views_for_all_days = eefstatify_get_views_for_all_days();
		$views_for_all_months = eefstatify_get_views_for_all_months();
		
		foreach( $days as $day ) {
			$day_line = array();
			array_push( $day_line, $day );
			foreach( $months as $month ) {
				$views = eefstatify_get_number_for_csv ( eefstatify_get_daily_views( $views_for_all_days, $year, $month, $day ) );
				array_push( $day_line, $views );
			}
			array_push( $result, $day_line );
		}
		
		$month_line = array( __( 'Sum', 'extended-evaluation-for-statify' ) );
		foreach ( $months as $month ) {
			array_push( $month_line, eefstatify_get_monthly_views( $views_for_all_months, $year, $month ) );
		}
		array_push( $result, $month_line );
		
		return $result;
	}
	
	/**
	 * Returns the monthly data for the csv file, including headlines.
	 * 
	 * @return multitype: an array of arrays containing the data
	 */
	function eefstatify_get_monthly_data_for_csv() {
		$months = eefstatify_get_months();
		$years = eefstatify_get_years();
		
		$result = array();
		$headline = array();
		array_push( $headline, __( 'Year', 'extended-evaluation-for-statify' ) );
		foreach ( $months as $month) {
			array_push( $headline, eefstatify_get_month_name( $month ) );
		}
		array_push( $headline, __( 'Sum', 'extended-evaluation-for-statify' ) );
		array_push( $result, $headline );
	
		$views_for_all_months = eefstatify_get_views_for_all_months();
		$views_for_all_years = eefstatify_get_views_for_all_years();
		
		foreach( $years as $year ) {
			$year_line = array();
			array_push( $year_line, $year );
			foreach ( $months as $month ) {
				array_push( $year_line, eefstatify_get_monthly_views( $views_for_all_months, $year, $month ) );
			}
			array_push( $year_line, eefstatify_get_yearly_views( $views_for_all_years, $year ) );
			array_push( $result, $year_line );
		}
		
		return $result;
	}

	/**
	 * Returns the content data for the csv file, including headlines.
	 * 
	 * @return multitype: an array of arrays containing the data
	 */
	function eefstatify_get_content_data_for_csv( $start = '', $end = '' ) {
		$result = array();
		array_push( $result,
				array(
						__( 'Title', 'extended-evaluation-for-statify' ),
						__( 'URL', 'extended-evaluation-for-statify' ),
						__( 'Post Type', 'extended-evaluation-for-statify' ),
						__( 'Views', 'extended-evaluation-for-statify' )
				)
		);
		
		if ( $start == '' && $end == '' ) {
			$target_views = eefstatify_get_views_of_most_popular_posts();
		} else {
			$target_views = eefstatify_get_views_of_most_popular_posts_for_period( $start, $end );
		}
		
		foreach ( $target_views as $target ) {
			array_push( $result, 
					array( 
							eefstatify_get_post_title_from_url( $target['url'] ), 
							$target['url'], 
							eefstatify_get_post_type_name_from_url( $target['url'] ),
							$target['count']
					)
			);
		}
		
		return $result;
	}
	
	/**
	 * Returns the post type content data for the csv file, including headlines.
	 * 
	 * @param unknown $post_type the post type to export
	 * @return multitype: an array of arrays containing the data
	 */
	function eefstatify_get_post_type_content_data_for_csv( $post_type, $start = '', $end = '' ) {
		$result = array();
		array_push( $result,
				array(
						__( 'Title', 'extended-evaluation-for-statify' ),
						__( 'URL', 'extended-evaluation-for-statify' ),
						__( 'Post Type', 'extended-evaluation-for-statify' ),
						__( 'Views', 'extended-evaluation-for-statify' )
				)
		);
		
		$post_type_name = get_post_type_object( $post_type )->labels->singular_name;
		$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => -1
		);
		$query = new WP_Query( $args );
		if( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				if ( $start == '' && $end == '' ) {
					$views = eefstatify_get_views_of_post( str_replace( home_url(), "", get_the_permalink() ) );
				} else {
					$views = eefstatify_get_views_of_post_for_period( str_replace( home_url(), "", get_the_permalink() ), $start, $end );
				}
				$post_line = array(
						get_the_title(),
						get_the_permalink(),
						$post_type_name,
						$views
				);
				array_push( $result, $post_line );
			endwhile;
		}
		
		return $result;
	}

	/**
	 * Returns the referrer data for the csv file, including headlines.
	 * 
	 * @return multitype: an array of arrays containing the data
	 */
	function eefstatify_get_referrer_data_for_csv( $start = '', $end = '') {
		$result = array();
		array_push( $result, 
				array( 
						__( 'Referring Domain', 'extended-evaluation-for-statify' ), 
						__( 'Views', 'extended-evaluation-for-statify' ) 
				)
		);
		
		if ( $start == '' && $end == '' ) {
			$referrer_views = eefstatify_get_views_for_all_referrers();
		} else {
			$referrer_views = eefstatify_get_views_for_all_referrers_for_period( $start, $end );
		}
		
		foreach( $referrer_views as $referrer ) {
			array_push( $result, 
					array(
							$referrer['host'],
							$referrer['count']
					)
			);
		}	
		
		return $result;
	}

	/**
	 * Converts the given array of array to the csv syntax (semicolon as separator)
	 * 
	 * @param unknown $lines an array of array
	 * @return string|boolean the csv content or FALSE on failure
	 */
	function eefstatify_create_csv( $lines ){
		if( is_array( $lines ) ):
			$output = '';
			foreach( $lines AS $response_id => $line ):
				$output .= '"' . implode( '";"', $line ) . '";' . chr( 13 );
			endforeach;
			return $output;
		else:
			return FALSE;
		endif;
	}
	