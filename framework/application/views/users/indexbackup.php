<?php echo $html->includeCss('ui.mainpage'); ?>
<div class="ui-container">
    <div id="ui-mainPage-container">
        <span id="ui-mainPage-welcome-text">
                Connecting agents and tenants
        </span>
        <span id="ui-mainPage-sub-text">
                <span class="green">Find exclusive rental listings through agents.</span>
                <span class="blue">Tenants sign up free.</span>
        </span>
        <div id="ui-mainPage-body">		
            <div id="ui-mainPage-search">
                <form action="/users/signup" method="post">
                    <h2 id="ui-mainpage-start-text" class="blue">Tenants, find the perfect rental.</h2>
                    <select name="desired_locale-city" id="inactive" class="ui-mainpage-select">
                        <?php
                            echo performAction('locales','ajax_city',array('NY'));
                        ?>
                    </select>
                    <select name="desired_locale-state" class="ui-mainpage-select">
                        <?php
                        $str = '';
                        $theStates = returnStates();
                        foreach($theStates as $key=>$state) {
                            $stateSelected = ($key=='NY') ? ' selected="SELECTED"' : '';
                            $str.= '<option value="'.$key.'"'.$stateSelected.'>'.$state.'</option>';
                        }
                        echo $str;
                        ?>
                    </select>
                    <input type="hidden" name="user_type" value="0" />
                    <input type="submit" name="tenant-signup" value="Move!" id="ui-mainpage-go" />
                    
                    <table id="ui-mainpage-signup-tenant-options">
                    	<tr>
                    		<td>
                    			<label>Rent ($)</label>
                    			<input type="text" name="max_rent" size="5" />
                    		</td>
                    		<td>
		                    	<label>Adults</label>
		                    	<select name="occupants_adults">
									<?php
									for($i=1; $i<=8; $i++) {
										echo '<option value="'.$i.'">'.$i.'</option>';	
									}
									?>
								</select>
							</td>
                    		<td>
                    			<label>Bedrooms</label>
		                    	<select name="desired_bedrooms">
		                    		<?php for($i=0;$i<=8;$i++) {
		                    			if($i==0)
											$v='Studio';
										else
											$v=$i;
                                                        $selected = ($i==1) ? ' selected="SELECTED"' : '';
		                    			echo '<option value="'.$i.'"'.$selected.'>'.$v.'</option>';
		                    		} ?>
		                    	</select>
		                    </td>
		                    <td>
		                    	<label>Credit</label>
		                    	<select name="credit_snapshot">
                                            <option value="">N/A</option>
									<option value="1">Marginal</option>
									<option value="2">Good</option>
									<option value="3">Excellent</option>
								</select>
							</td>
                    		<td>
		                    	<label>Program</label>
		                    	<select name="program">
									<option value="">None</option>
									<option value="1">Section 8</option>
									<option value="2">BAH</option>
									<option value="3">Other</option>
								</select>
							</td>
                    	</tr>
                    </table>
                </form>
            </div>
            <div id="ui-mainPage-signUp">
                <div id="ui-mainPage-signUp-title">
                        <h4>Real Estate Agents</h4>
                        <h5>Represent tenants that sign up or add your own</h5>
                </div>
                <form action="/users/signup" method="post" enctype="multipart/form-data" name="signUp">
                    <input type="hidden" name="user_type" value="1" />
                    <div id="ui-mainPage-signUp-formContainer">
                        <table id="ui-mainPage-signUp-form">
                                <tr>
                                        <td><label for="name">Name</label></td>
                                        <td>
                                                <input type="text" name="name" />
                                        </td>
                                </tr>
                                <tr>
                                        <td><label for="email">Email</label></td>
                                        <td>
                                                <input type="text" name="email" />
                                        </td>
                                </tr>
                                <tr>
                                        <td><label for="password">Password</label></td>
                                        <td>
                                                <input type="password" name="password" />
                                        </td>
                                </tr>
                                <tr>
                                        <td><label for="company">Company</label></td>
                                        <td>
                                                <input type="text" name="company" />
                                        </td>
                                </tr>
                                <tr>
                                        <td><label for="license_status">License</label></td>
                                        <td>
                                                <select name="license_status">
                                                        <option value="0">Agent</option>
                                                        <option value="1">Associate Broker</option>
                                                        <option value="2">Broker</option>
                                                </select>
                                        </td>
                                </tr>
                        </table>
                        <span id="ui-mainpage-signup-bottom-text">or <a href="/pages/about">learn more</a></span>
                        <input type="submit" name="signUp_submit" id="ui-mainPage-signUp-submit" value="Get Started" />
                        

                        <div class="clear"></div>
                    </div>
                </form>
                <div class="clear"></div>
            </div>
            <div id="ui-mainpage-tabs-container">
                <ul id="ui-mainpage-tabs">
                    <li id="why_join" class="active">Why Join</li>
                    <li id="search">Search Tenants</li>
                    <li id="agents">Top Agents</li>
                </ul>
                <div class="clear"></div>
            </div>
            <div id="ui-mainpage-tabs-content-container">
                <ul id="ui-mainpage-tabs-content">
                    <li id="content-why_join" class="active">
                        <table id="ui-mainPage-reasonsToJoin">
                            <tr>
                                <td>
                                        <img src="images/ui-mainPage-icon-search.png" />
                                        <h3>1. Find Quality Tenants</h3>
                                        <h5>Tenants sign up to find local rental agents who represent them as an open or exclusive client.</h5>
                                </td>
                                <td>
                                        <img src="images/ui-mainPage-icon-profile.png" />
                                        <h3>2. Manage Tenant Profiles</h3>
                                        <h5>Supply your tenant's profile with proof of income, credit, and other information to give to a potential landlord.</h5>
                                </td>
                                <td>
                                        <img src="images/ui-mainPage-icon-rental.png" />
                                        <h3>3. Hot Home Buyer Leads</h3>
                                        <h5>Quality tenants are the perfect candidates for first time home buyers - automatically archive past tenants for hot future leads.</h5>
                                </td>
                            </tr>
                        </table>
                    </li>
                    <li id="content-search">
                        <h4>Search me, search me!</h4>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
