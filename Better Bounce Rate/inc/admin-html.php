<div class="wrap">
	<h1>Better Bounce Rate</h1>
	<div class="dashboard-card">
	<form id="bbr" autocomplete="off" method="POST">
		<input type="hidden" name="action" value="guardar_bbr_AJAX">
	<table class="form-table">
		<tbody>
		<b>Status: <?php echo (get_option('bbr_configured') === 'on') ? '<b style="color:green">Enabled</b>' : '<b style="color:red">Disabled</b>'; ?></b>
			<tr>
				<th scope="row">
					<label for="trid">Tracking Code ID</label>
					<div class="help">
						<span class="help-text">?</span>
						<div class="tooltip tt-bg">
							<p>Find your Tracking ID Code</p>
							<p>
1.- In Admin, under Data collection and modification, click Data streams.<br/>
2.- Select the Web tab.<br/>
3.- Click the web data stream.<br/>
4.- Find the measurement ID in the first row of the stream details.<br/>
							</p>
							<img src="<?php echo esc_url( plugins_url( '../assets/analytics.png', __FILE__ ) ); ?>" width="100%">
						</div>
					</div>
				</th>
				<td>
					<input name="trid" id="trid" type="text" class="regular-text" placeholder="G-XXXX-XX" value="<?php echo esc_attr( isset( $settings['id'] ) ? $settings['id'] : '' ); ?>" required>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="toe">Time on event (minimum time required - milliseconds)</label>
					<div class="help">
						<span class="help-text">?</span>
						<div class="tooltip tt-md">
							<p>minimum time required to activate the bounce rate.</p>
							<p>This is expresed in milliseconds.</p>
							<p>For better results, add between 1000 and 5000.</p>
						</div>
					</div>
				</th>
				<td>
					<input name="toe" id="toe" type="number" min="1" class="regular-text" value="<?php echo esc_attr( isset( $settings['time'] ) ? $settings['time'] : '' ); ?>" placeholder="10000" required>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="mcode">
					<input name="mcode" type="checkbox" id="mcode" <?php echo $settings['mcode']=='on'?'checked':'' ?>>I want to add more code</label>
				</th>
			</tr>

			<tr class="complementary-code">
				<th scope="row">
					<label for="ccode">Complementary code</label>
				</th>
				<td>
					<textarea name="ccode" id="ccode" cols="30" rows="10" class="regular-text" placeholder="Paste your Complementary code to your Google Analytics" required><?php echo isset($settings['ccode']) ? wp_kses(stripslashes_deep($settings['ccode']), array()) : ''; ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
			</div>
	<p class="submit">
		<input type="submit" name="send" id="send" value="Save settings" class="button button-primary">
	</p>
	</form>
	<div id="respuesta">
		<div class="notice is-dismissible" style="display:none;"></div>
	</div>
</div>




<script type="text/javascript">
jQuery(document).ready(function($) {
	function checkComplementaryCode() {
		if ($("#mcode").prop('checked') === true) {
			$(".complementary-code").show();
			$("#ccode").prop('disabled', false);
		}
		else {
			$(".complementary-code").hide();
			$("#ccode").prop('disabled', true);
		}
	}
	checkComplementaryCode();

	function showNotice(comprobacion) {
		if(comprobacion) {
			$(".notice").removeClass('notice-error').addClass("notice-success").html('<p>Saved Settings</p><button type="button" class="notice-dismiss"></button>').show();
		} else {
			$(".notice").removeClass('notice-success').addClass("notice-error").html('<p>Error</p><button type="button" class="notice-dismiss"></button>').show();
		}
	}

	// Tooltip
	$(".help-text").on("mouseover", function() {
		$(this).next().toggle();
	}).on("mouseout", function() {
		$(this).next().hide();
	}).on("click", function() {
		$(this).next().toggle();
	});

	// Limitar cifras
	$("#toe").on("keydown", function(e) {
		if ($(this).val().length >= 10 && e.keyCode !== 8) return false;
	});

	// Función agregar código adicional
	$("#mcode").on("click", checkComplementaryCode);

	$("#bbr").on("submit", function(e) {
		e.preventDefault();
		$("#send").prop('disabled', true);
		var datos = $(this).serialize();
		$.post(ajaxurl, datos, function(response) {
			var isTrueSet = (response == 'true');
			showNotice(isTrueSet);
			$("#send").prop('disabled', false);
		});
	});

	$(document.body).on("click", ".notice-dismiss", function(e) {
		$(".notice").hide();
	});
});
</script>
<style type="text/css">
.help {
    display: inline-block;
    margin-left: 5px;
}
.dashboard-card {
    border: 1px solid #ddd;
    padding: 20px;
    margin-bottom: 20px;
    background-color: #fff;
}	
.help-text {
    color: #000;
    border: 1px solid #000;
    width: 17px;
    line-height: 17px;
    text-align: center;
    height: 17px;
    display: block;
    cursor: pointer;
    border-radius: 100%;
}
.tooltip {
	display: none;
    position: absolute;
    background: #000;
    color: #EEE;
    padding: 10px;
    border-radius: 5px;
    margin: -30px 0px 0px 40px;
}
.tooltip:before {
    content: '';
    width: 20px;
    height: 20px;
    background: #000;
    margin-left: -20px;
    margin-top: 0%;
    transform: rotate(45deg);
    display: block;
    float: left;
    z-index: 9998;
}
.help * {
    user-select: none;
}
.tt-xb {
	width: 500px;
}
.tt-bg {
	width: 400px;
}
.tt-md {
	width: 300px;
}
.tt-sm {
	width: 200px;
}
.tt-xs {
	width: 100px;
}
td {
    width: 70%;
    padding: 10px;
    vertical-align: top;
}	
</style>