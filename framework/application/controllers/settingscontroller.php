<?php
class SettingsController extends Controller {
    function create_entry() {
        return $this->Setting->save(true);
    }
	/* 
	 * Faux function
	 */
	 function register() {
	 	// LOL I DO NOTHING!
	 }
}
?>