<?php
global $wp;
$current_url = wp_parse_url(home_url());

// Obtener los ajustes de Better Bounce Rate
$settings = get_option('bbr_settings');

// Sanitizar y escapar los datos
$id = isset($settings['id']) ? esc_attr($settings['id']) : '';
$time = isset($settings['time']) ? absint($settings['time']) : 0;
$ccode = isset($settings['ccode']) ? wp_kses_post($settings['ccode']) : '';

?>
<!-- Better Bounce Rate for Analytics V4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($id); ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo esc_attr($id); ?>', {
    'page_hostname': '<?php echo isset($current_url['host']) ? esc_attr($current_url['host']) : ''; ?>'
  });

  setTimeout(function() {
    gtag('event', 'LowBounce', {
      'event_category': 'LowBounce',
      'event_label': 'LowBounce'
    });
  }, 10 * <?php echo esc_js($time); ?>);

  <?php echo !empty($ccode) ? stripslashes($ccode) : ''; ?>
</script>
<!-- Better Bounce Rate for Analytics V4 -->
