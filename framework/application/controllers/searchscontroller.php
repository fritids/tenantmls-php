<?php
class SearchsController extends Controller {
	function beforeAction() {
	}
	function afterAction() {
		
	}
	
	function search($locale_query = null, $filter_query = null) {
		$locale_array = explode('-',$locale_query);
		$this->set('query_data',$locale_array);
		
		$raw_filter_array = explode('-',$filter_query);
		$filter_array = array();
		foreach($raw_filter_array as $filter) {
			$filter = explode('=',$filter);
			array_push($filter_array, array('filter_name'=>$filter[0],'filter_value'=>$filter[1]));
		}
		$this->set('filter_data',$filter_array);
		
		$this->set('query_results',performAction('profiles','search',array($locale_query,$filter_query)));
	}
}
?>