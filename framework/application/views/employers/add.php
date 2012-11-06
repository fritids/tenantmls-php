<div id="ui-employer-container">
	<script>
	$(document).ready(function() {
		$('form[name="addEmployer"]').submit(function() {
			var sendData = $(this).serialize();
			$.post($(this).attr('action'), sendData, function(data) {
				// flash saved
				$('#editing').html('Saved').parent().children('td').css({'background-color' : "#FFFF9C"}).delay(500);
				$('#editing').parent().children('td').animate({'background-color':'#FFFFFF'},1000).parent().children('td:last-child').html(data).attr('id','');
				//$('#editing').delay(10000).html(data);
			});
			return false;
		});
	});
	</script>
	<form action="/employers/add/<?php echo $data['User']['id']; ?>" method="post" name="addEmployer">
	<table id="ui-employer-add-table">
		<tr>
			<td>Company</td>
			<td><input type="text" name="company_name" /></td>
		</tr>
		<tr>
			<td>Income</td>
			<td>
				$<input type="text" name="pay_amount" size="6">
				<select name="pay_period">
					<option value="W">weekly</option>
					<option value="B">biweekly</option>
					<option value="M">monthly</option>
					<option value="Q">quarterly</option>
					<option value="H">semi-annually</option>
					<option value="A">annually</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Employer Name</td>
			<td><input type="text" name="employer_name" /></td>
		</tr>
		<tr>
			<td>Phone #</td>
			<td>(<input type="text" name="employer_number_1" size="3" />) <input type="text" name="employer_number_2" size="3" />-<input type="text" name="employer_number_3" size="4" /></td>
		</tr>
		<tr>
			<td>Start Date</td>
			<td>
				<select name="start_month">
					<option value="">--</option>
					<?php
					$months = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
					foreach ($months as $k=>$name) {
						echo '<option value="'.$k.'">'.$k.'</option>';
					}
					?>
				</select>
				<select name="start_year">
					<option value="">----</option>
					<?php
					$year = date('Y');
					for($i=$year;$i>=$year-40;$i--) {
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>End Date</td>
			<td><input type="checkbox" name="present" value="1" checked="CHECKED" /> Present</td>
		</tr>
		<tr>
			<td>Description</td>
			<td>
				<textarea name="description" class="textarea-fill"></textarea>
			</td>
		</tr>
		<tr>
			<td>Proof of Income</td>
			<td>
				<input type="file" name="file_upload">
			</td>
		</tr>
	</table>
	<input type="submit" name="submit" value="Add Employer" />
	</form>
</div>
