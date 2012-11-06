<style>
	table {
	}
	table tr td {
		vertical-align:top;
		
		border:1px solid #272727;
	}
	table tr td:nth-child(even) {
		background-color:#e9e9e9;
	}
</style>
Output:<br />
<table>
	<tr>
		<td>
			<?php
			$field = (isset($runField)) ? "/$runField" : '';
			$sessionCookie = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
			$requestArray = array(
				'API_URL'=> BASE_PATH.'/api/'.$runFunction.'/'.$runQuery.'/'.$runField,
				'API_ID'=>API_ID,
				'API_SECRET'=>API_SECRET,
				'USER_SESSION'=>$sessionCookie,
				'RETURN_TYPE'=>'json'
			);
			$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
			$request->execute();
			$json_apidata = json_decode($request->getResponseBody(),true);
			?>
			<h4>JSON (Default)</h4>
			<textarea name="output" style="width:800px; height:500px;"><?php echo $request->getResponseBody(); ?></textarea>
		</td>
		<td>
			<h4>RestRequest</h4>
			<?php printr($request); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php
			
			$requestArray['RETURN_TYPE'] = 'xml';
			$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
			$request->execute();
			$xml_apidata = $request->getResponseBody();
			?>
			<h4>XML</h4>
			<textarea name="output" style="width:800px; height:500px;"><?php echo formatXmlString($xml_apidata); ?></textarea>
		</td>
		<td>
			<h4>RestRequest</h4>
			<?php printr($request); ?>
		</td>
	</tr>
</table>

<?php printr($xUserData); ?>
